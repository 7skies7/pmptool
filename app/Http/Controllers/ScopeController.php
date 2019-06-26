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
        return Scope::with('status')->where('is_deleted',0)->latest()->get();
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
        $attributes = request()->validate(['comment'=> 'required'
                                        ]);
        $attributes['created_by'] = auth()->user()->id;
        $attributes['modified_by'] = auth()->user()->id;
        $attributes['project_id'] = $request->get('project_id');
        $attributes['crd_id'] = $request->get('crdid');


        //check if file is attachment then save it to the directory and return filename
        if(!empty($request->get('file')))
        {
            $fileName = $this->saveFile($request);    
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
        $file = $request->get('file');
        $file_ext = explode('/', mime_content_type($file))[1];
        $file = str_replace('data:application/pdf;base64,', '', $file);
        $file = str_replace(' ', '+', $file);
        $fileName = time().'_'.$request->get('fileName');
        
        Storage::disk('public')->makeDirectory('CRD/'.$request->get('crdid'));
        \File::put(storage_path()."/app/public/CRD/".$request->get('crdid').'/'. $fileName, base64_decode($file));
        return $fileName;
    }

    public function fetchCommments($crdid)
    {
        $comments = ScopeComment::with('documents')->where('id', $crdid)->get();
        dd($comments);
        // foreach($comments as $comment)
        // {

        // }
    }
}
