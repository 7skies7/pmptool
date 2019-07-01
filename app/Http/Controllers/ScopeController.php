<?php

namespace App\Http\Controllers;

use App\Scope;
use App\Document;
use App\ScopeComment;
use App\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;
use Storage;


class ScopeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Authorize user requests to view all resource
        if(Gate::allows('View_Project') != true)
        {
            return abort('403');
        }
        // return Project::with('user')->latest()->get();
        return Scope::with('status')->with('approveddocument')->where('is_deleted',0)->latest()->get();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Authorize user requests to store resource details
        if(Gate::allows('Create_Scope') != true)
        {
            return abort('403');
        }

        //validate
        $attributes = request()->validate(['crd_title'=> 'required', 
                                            'crd_desc' => 'required',
                                            'crd_status' => 'required',
                                        ]);
        
        $attributes['created_by'] = auth()->user()->id;
        $attributes['modified_by'] = auth()->user()->id;
        $attributes['crd_status'] = $attributes['crd_status']['id'];
        $attributes['project_id'] = request('project_id');
        $lastScopeId = Scope::orderBy('id', 'desc')->limit(1)->pluck('id');
        $attributes['crd_id'] = 'CRD_'.request('project_id').'_'.$lastScopeId[0];
        //store scope details in database
        $scope = Scope::create($attributes);
        //save in session
        return $scope;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Project  $Project
     * @return \Illuminate\Http\Response
     */
    public function show(Project $Project)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Project  $Project
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //

        $scope = Scope::with('status')->find($id);        

        return json_encode($scope);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\  $Project
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Authorize user requests to update resource details
        if(Gate::allows('Update_Scope') != true)
        {
            return abort('403');
        }

        //validate
        $attributes = request()->validate(['crd_title'=> 'required', 
                                            'crd_desc' => 'required',
                                            'crd_status' => 'required',
                                        ]);

        $attributes['created_by'] = auth()->user()->id;
        $attributes['modified_by'] = auth()->user()->id;
        $attributes['crd_status'] = $attributes['crd_status']['id'];
            
        //store in database
        $project = Scope::where('id', $id)->update($attributes);

        //save in session
        return $project;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Project  $Project
     * @return \Illuminate\Http\Response
     */
    public function destroy($scopeid)
    {
        // Authorize user requests to delete resource
        if(Gate::allows('Delete_Scope') != true)
        {
            return abort('403');
        }
        
        $attributes['is_deleted'] = 1;
        $attributes['deleted_by'] = auth()->user()->id;
        $attributes['deleted_at'] = (new Carbon)->toDateTimeString();
        
        $scope = Scope::where('id', $scopeid)->update($attributes);
        
        return $scope;  
    }

    public function storeCommment(Request $request, $crdid)
    {
        $fileName = '';
        $document_id = '';
        $attributes = request()->validate(['comment'=> 'required']);
        $attributes['created_by'] = auth()->user()->id;
        $attributes['modified_by'] = auth()->user()->id;
        $attributes['project_id'] = $request->get('project_id');
        $attributes['crd_id'] = $request->get('crdid');


        //check if file is attachment then save it to the directory and return filename
        if(!empty($request->get('file')))
        {
            $fileName = $this->saveFile($request);
            if(is_array($fileName))
            {
                return response()->json($fileName, 422); 
            }    
            $attributes['file_name'] = $fileName;
            unset($attributes['comment']);
            $document = Document::create($attributes);
            $attributes['document_id'] = $document->id;
        }

        $attributes['comment'] = $request->get('comment');
        unset($attributes['file_name']);
        $scope = ScopeComment::create($attributes);
        return $scope;
    }

    public function saveFile($request)
    {
        $validExtensions = ['img', 'jpg', 'jpeg', 'docx', 'pdf', 'vnd.openxmlformats-officedocument.spreadsheetml.sheet'];
        $file = $request->get('file');
        $file_ext = explode('/', mime_content_type($file))[1];
        if(!in_array($file_ext, $validExtensions)) {
            $returnData = array(
                'status' => 'error',
                'message' => 'Please upload only pdf, jpg, png and docx files only',
                'file_ext' => $file_ext
            );
            return $returnData;
        }
        $file = preg_replace('/^data:.*php$/', '', $file);
        $file = str_replace(' ', '+', $file);
        $fileName = time().'_'.$request->get('fileName');
        
        Storage::disk('public')->makeDirectory('CRD/'.$request->get('crdid'));
        \File::put(storage_path()."/app/public/CRD/".$request->get('crdid').'/'. $fileName, base64_decode($file));
        return $fileName;
    }

    public function fetchCommments($crdid)
    {
        $commentsArr = [];
        $comments = ScopeComment::with('documents')->with('users')->where('crd_id', $crdid)->get();
        

        foreach($comments as $comment)
        {
            $commentArr['id'] = $comment->id;
            $commentArr['username'] = $comment->users[0]->first_name.' '.$comment->users[0]->last_name;
            $commentArr['text'] = $comment->comment;
            $commentArr['time'] = Carbon::createFromFormat('Y-m-d H:i:s',  $comment->created_at)->format('F j, Y');
            $commentArr['filename'] = null;
            if(!empty($comment->documents) && isset($comment->documents[0]))
            {
                $commentArr['filename'] = $comment->documents[0]->file_name;
            }
            $commentsArr[] = $commentArr;

        }
        return $commentsArr;

    }

    public function fetchDocuments($crdid)
    {
        return Document::select('id','file_name')->where('crd_id', $crdid)->get();
    }

    public function approveDocument($crdid)
    {
        
        //$approveDoc = Document::where('id',request('approved_document')['id'])->update(['file_status' => 1]);
        $attributes['approved_document'] = request('approved_document')['id'];
        $attributes['crd_status'] = 2; //Change from in planning to Approved
        return Scope::where('id', $crdid)->update($attributes);
        
    }

    public function fetchApprovedDocument($crdid)
    {
        return Scope::find($crdid)->approveddocument;
    }
}
