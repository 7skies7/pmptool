<?php

namespace App\Http\Controllers;

use App\Project;
use App\ProjectStakeholder;
use App\ProjectManager;
use App\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;

class ProjectController extends Controller
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
        return Project::with('status')->where('is_deleted',0)->latest()->get();
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
        if(Gate::allows('Create_Project') != true)
        {
            return abort('403');
        }

        //validate
        $attributes = request()->validate(['project_name'=> 'required', 
                                            'project_start_date' => 'required',
                                            'project_end_date' => 'required',
                                            'project_desc' => 'required',
                                            'project_managers' => 'required',
                                            'project_status' => 'required',
                                            'project_stakeholders' => 'required',
                                        ]);
        
        $attributes['created_by'] = auth()->user()->id;
        $attributes['modified_by'] = auth()->user()->id;
        $attributes['project_status'] = $attributes['project_status']['id'];
        $attributes['project_budget'] = request('project_budget');
        $attributes['project_start_date'] = Carbon::parse($attributes['project_start_date'])->format('Y-m-d');
        $attributes['project_end_date'] = Carbon::parse($attributes['project_end_date'])->format('Y-m-d');
        // $managers = request('project_manager');
        // $attributes['project_manager'] = implode(', ', array_map(function ($manager) {
        //     return $manager['id'];
        // }, $managers));
        
        //store project details in database
        unset($attributes['project_managers']);
        unset($attributes['project_stakeholders']);
        
        $project = Project::create($attributes);
        
        foreach(request('project_managers') as $manager)
        {
            $managerArr['project_id'] =  $project->id;
            $managerArr['user_id'] = $manager['id'];
            ProjectManager::create($managerArr);
        }
        foreach(request('project_stakeholders') as $stakeholder)
        {
            $stakeholderArr['project_id'] =  $project->id;
            $stakeholderArr['user_id'] = $stakeholder['id'];
            ProjectStakeholder::create($stakeholderArr);
        }
        //save in session
        return $project;
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

        $project = Project::with('status')->with('managers')->find($id);

        if(!empty($project->managers))
        {
            $project_manager = [];
            foreach($project->managers as $manager)
            {
                $project_manager[] = User::select('id',\DB::raw("CONCAT(users.first_name,' ',users.last_name) as name"))->find($manager->user_id);
            } 
            $project['project_managers'] = $project_manager;
        }
        if(!empty($project->stakeholders))
        {
            $project_stakeholder = [];
            foreach($project->stakeholders as $stakeholder)
            {
                $project_stakeholder[] = User::select('id',\DB::raw("CONCAT(users.first_name,' ',users.last_name) as name"))->find($stakeholder->user_id);
            } 
            $project['project_stakeholders'] = $project_stakeholder;
        }    
        

        return json_encode($project);
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
        if(Gate::allows('Update_Project') != true)
        {
            return abort('403');
        }

        //validate
        $attributes = request()->validate(['project_name'=> 'required', 
                                            'project_start_date' => 'required',
                                            'project_end_date' => 'required',
                                            'project_desc' => 'required',
                                            'project_managers' => 'required',
                                            'project_status' => 'required',
                                            'project_stakeholders' => 'required',
                                        ]);

        $attributes['project_status'] = $attributes['project_status']['id'];
        $attributes['project_budget'] = request('project_budget');
        $attributes['project_start_date'] = Carbon::parse($attributes['project_start_date'])->format('Y-m-d');
        $attributes['project_end_date'] = Carbon::parse($attributes['project_end_date'])->format('Y-m-d');

        
        $attributes['modified_by'] = auth()->user()->id;
            
        //store in database
        unset($attributes['project_managers']);
        unset($attributes['project_stakeholders']);
        $project = Project::where('id', $id)->update($attributes);
    
        //Delete previous project records
        ProjectManager::where('project_id',$id)->delete();
        ProjectStakeholder::where('project_id',$id)->delete();
        
        foreach(request('project_managers') as $manager)
        {
            $managerArr['project_id'] =  $id;
            $managerArr['user_id'] = $manager['id'];
            ProjectManager::create($managerArr);
        }
        foreach(request('project_stakeholders') as $stakeholder)
        {
            $stakeholderArr['project_id'] =  $id;
            $stakeholderArr['user_id'] = $stakeholder['id'];
            ProjectStakeholder::create($stakeholderArr);
        }

        //save in session
        return $project;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Project  $Project
     * @return \Illuminate\Http\Response
     */
    public function destroy($projectid)
    {
        // Authorize user requests to delete resource
        if(Gate::allows('Delete_Project') != true)
        {
            return abort('403');
        }
        
        $attributes['is_deleted'] = 1;
        $attributes['deleted_by'] = auth()->user()->id;
        $attributes['deleted_at'] = (new Carbon)->toDateTimeString();
        
        $project = Project::where('id', $projectid)->update($attributes);
        
        return $project;  
    }
}
