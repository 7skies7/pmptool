<?php

namespace App\Http\Controllers;

use App\Userstory;
use App\Priority;
use App\Document;
use App\ScopeComment;
use App\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;
use Storage;

class UserstoryController extends Controller
{
    // Authorize user requests to view all resource
    public function index($scopeid)
    {
    	if(Gate::allows('View_Userstory') != true)
		{
		    return abort('403');
		}
		// return Project::with('user')->latest()->get();
		return Userstory::with('status')->with('priority')->where('is_deleted',0)->where('cr_id', $scopeid)->get();	
    }
    
    public function store(Request $request)
    {
        // Authorize user requests to store resource details
        if(Gate::allows('Create_Userstory') != true)
        {
            return abort('403');
        }

        //validate
        $attributes = request()->validate(['userstory_desc'=> 'required', 
                                            'userstory_point' => 'required|digits_between:1,200',
                                            'userstory_status' => 'required',
                                            'userstory_priority' => 'required',
                                        ]);
        
        $attributes['created_by'] = auth()->user()->id;
        $attributes['modified_by'] = auth()->user()->id;
        $attributes['userstory_status'] = $attributes['userstory_status']['id'];
        $attributes['userstory_priority'] = $attributes['userstory_priority']['id'];
        $attributes['project_id'] = request('project_id');
        $attributes['initial_story_point'] = request('userstory_point');
        $attributes['cr_id'] = request('cr_id');
        $userrecord = Userstory::orderBy('id', 'desc')->limit(1)->pluck('id');
        $lastStoryId = isset($userrecord[0]) ? $userrecord[0] : 0;
        $attributes['userstory_id'] = 'US_'.request('cr_id').'_'.($lastStoryId + 1);
        //store scope details in database
        $userstory = Userstory::create($attributes);
        //save in session
        return $userstory;
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

        $scope = Userstory::with('status')->with('priority')->find($id);        

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
        if(Gate::allows('Update_Userstory') != true)
        {
            return abort('403');
        }

        //validate
        $attributes = request()->validate(['userstory_desc'=> 'required', 
                                            'userstory_point' => 'required',
                                            'userstory_status' => 'required',
                                            'userstory_priority' => 'required',
                                        ]);

		$attributes['created_by'] = auth()->user()->id;
        $attributes['modified_by'] = auth()->user()->id;
        $attributes['userstory_status'] = $attributes['userstory_status']['id'];
        $attributes['userstory_priority'] = $attributes['userstory_priority']['id'];
            
        //store in database
        $userstory = Userstory::where('id', $id)->update($attributes);

        //save in session
        return $userstory;
    }

        /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Project  $Project
     * @return \Illuminate\Http\Response
     */
    public function destroy($userstoryid)
    {
        // Authorize user requests to delete resource
        if(Gate::allows('Delete_Userstory') != true)
        {
            return abort('403');
        }
        
        $attributes['is_deleted'] = 1;
        $attributes['deleted_by'] = auth()->user()->id;
        $attributes['deleted_at'] = (new Carbon)->toDateTimeString();
        
        $userstory = Userstory::where('id', $userstoryid)->update($attributes);
        
        return $userstory;  
    }

    public function storeCommment(Request $request, $userstoryid)
    {
        $fileName = '';
        $document_id = '';
        $attributes = request()->validate(['comment'=> 'required']);
        $attributes['created_by'] = auth()->user()->id;
        $attributes['modified_by'] = auth()->user()->id;
        $attributes['project_id'] = $request->get('project_id');
        $attributes['crd_id'] = $request->get('cr_id');
        $attributes['userstory_id'] = $request->get('userstory_id');

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
        $attributes['userstory_point'] = $request->get('userstory_point');
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
        
        Storage::disk('public')->makeDirectory('CRD/'.$request->get('userstory_id'));
        \File::put(storage_path()."/app/public/CRD/".$request->get('userstory_id').'/'. $fileName, base64_decode($file));
        return $fileName;
    }

    public function fetchCommments($userstoryid)
    {
        $commentsArr = [];
        $comments = ScopeComment::with('documents')->with('users')->where('userstory_id', $userstoryid)->get();
        
        $approved_comment = Userstory::find($userstoryid)->pluck('approved_comment');
        
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
            $commentArr['userstory_point'] = null;
            if(!empty($comment->userstory_point) && isset($comment->userstory_point))
            {
                $commentArr['userstory_point'] = $comment->userstory_point;
            }
            $commentArr['approved'] = null;
            if(!empty($approved_comment) && isset($approved_comment[0]) && $approved_comment[0] == $comment->id)
            {
                $commentArr['approved'] = $approved_comment[0];
            }
            $commentsArr[] = $commentArr;

        }
        return $commentsArr;

    }

    public function fetchDocuments($userstoryid)
    {
        return Document::select('id','file_name')->where('userstory_id', $userstoryid)->get();
    }

    public function approveDocument($userstoryid)
    {
        
        $commentid = request('commentid');
        $comment = ScopeComment::find($commentid);
        $attributes['approved_comment'] = $commentid;
        $attributes['userstory_point'] =  $comment->userstory_point;
        $attributes['userstory_status'] = 2; //Change from in planning to Approved
        return Userstory::where('id', $userstoryid)->update($attributes);
        
    }

    public function fetchApprovedDocument($userstoryid)
    {
        return Userstory::find($userstoryid)->approveddocument;
    }

    public function getStory($crdid)
    {
        return Scope::find($crdid);
    }
}
