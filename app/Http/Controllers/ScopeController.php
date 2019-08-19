<?php

namespace App\Http\Controllers;

use App\Scope;
use App\Userstory;
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
    public function index($project_id)
    {
        // Authorize user requests to view all resource
        if(Gate::allows('View_Scope') != true)
        {
            return abort('403');
        }
        // return Project::with('user')->latest()->get();
        // return Scope::with('status')->with('approveddocument')->where('is_deleted',0)->latest()->get();
        return Scope::with('status')->where('is_deleted',0)->where('project_id', $project_id)->latest()->get();

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
        $lastScope = Scope::orderBy('id', 'desc')->limit(1)->pluck('id');
        $lastScopeId = isset($lastScope[0]) ? $lastScope : 0;
        $attributes['crd_id'] = 'CR_'.request('project_id').'_'.($lastScopeId[0] + 1);
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
    
        $userstory = Userstory::where('cr_id', $scopeid)->count();
        if($userstory > 0)
        {
            $message['errors']['message'] = 'Sorry! Scope cannot be deleted as it has userstory associated with it.';
            return response()->json($message, 422); 
        }
        
        $attributes['is_deleted'] = 1;
        $attributes['deleted_by'] = auth()->user()->id;
        $attributes['deleted_at'] = (new Carbon)->toDateTimeString();
        
        $scope = Scope::where('id', $scopeid)->update($attributes);
        
        return $scope;  
    }

    public function getProjectScope($project_id)
    {
        return Scope::select('id', 'crd_title', 'crd_id')->where('is_deleted',0)->where('project_id', $project_id)->where('crd_status', 2)->latest()->get();
    }

    public function getScopeUserstory($scope_id)
    {
        return Userstory::select('id', 'userstory_desc', 'userstory_id')->where('is_deleted',0)->where('cr_id', $scope_id)->where('userstory_status', 2)->latest()->get();
    }
    
}
