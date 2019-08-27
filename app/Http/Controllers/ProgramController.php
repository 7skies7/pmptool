<?php

namespace App\Http\Controllers;

use App\Program;
use App\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;
use App\ProgramManager;
use App\UserRole;
use App\CompanyManager;
use App\Project;
use DB;
class ProgramController extends Controller
{
    protected $program_role_id = 6;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Authorize user requests to view all resource
        if(Gate::allows('View_Program') != true)
        {
            return abort('403');
        }
        
        if(User::isRole(1) || User::isRole(2))
        {
            return Program::with('managers')->with('company')->where('is_deleted',0)->latest()->get();
        }

        if(User::isRole(5))
        {            
            $companies = CompanyManager::where('user_id', auth()->user()->id)->pluck('company_id');
            return Program::with('managers')->with('company')->whereIn('company_id', $companies)->where('is_deleted',0)->latest()->get();
        }

        
        $programs = ProgramManager::where('user_id', auth()->user()->id)->pluck('program_id');
        return Program::with('managers')->with('company')->whereIn('id', $programs)->where('is_deleted',0)->latest()->get();

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
        if(Gate::allows('Create_Program') != true)
        {
            return abort('403');
        }

        //validate
        $attributes = request()->validate(['program_name'=> 'required', 
                                            'program_start_date' => 'required',
                                            'program_end_date' => 'required',
                                            'program_desc' => 'required',
                                            'program_manager' => 'required',
                                            'company' => 'required',
                                        ]);
        
        $attributes['created_by'] = auth()->user()->id;
        $attributes['modified_by'] = auth()->user()->id;
        $attributes['company_id'] = $attributes['company']['id'];
               
        
        //store in database
        unset($attributes['program_manager']);
        unset($attributes['company']);
        $program = Program::create($attributes);

        foreach(request('program_manager') as $manager)
        {
            $managerArr['program_id'] =  $program->id;
            $managerArr['user_id'] = $manager['id'];
            ProgramManager::create($managerArr);
            //Assign Program Manager role to all the program managers selected
            $roleArr['user_id'] = $manager['id'];
            $roleArr['role_id'] = $this->program_role_id;
            UserRole::firstOrCreate($roleArr,$roleArr);
        }

        //save in session
        return $program;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Program  $Program
     * @return \Illuminate\Http\Response
     */
    public function show(Program $Program)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Program  $Program
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $program = Program::with('managers')->find($id);
        return json_encode($program);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\  $Program
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Authorize user requests to update resource details
        if(Gate::allows('Update_Program') != true)
        {
            return abort('403');
        }

        //validate
        $attributes = request()->validate(['program_name'=> 'required', 
                                            'program_start_date' => 'required',
                                            'program_end_date' => 'required',
                                            'program_desc' => 'required',
                                            'program_manager' => 'required',
                                            'company' => 'required',
                                        ]);

        $attributes['company_id'] = $attributes['company']['id'];
        $attributes['modified_by'] = auth()->user()->id;
            
        //store in database
        unset($attributes['program_manager']);
        unset($attributes['company']);
        $program = Program::where('id', $id)->update($attributes);

        //Delete previous company records
        ProgramManager::where('program_id',$id)->delete();

        foreach(request('program_manager') as $manager)
        {
            $managerArr['program_id'] =  $id;
            $managerArr['user_id'] = $manager['id'];
            ProgramManager::create($managerArr);
            //Assign Program Manager role to all the program managers selected
            $roleArr['user_id'] = $manager['id'];
            $roleArr['role_id'] = $this->program_role_id;
            UserRole::firstOrCreate($roleArr,$roleArr);
        }
        //save in session
        return $program;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Program  $Program
     * @return \Illuminate\Http\Response
     */
    public function destroy($programid)
    {
        // Authorize user requests to delete resource
        if(Gate::allows('Delete_Program') != true)
        {
            return abort('403');
        }
        
        $project = Project::where('program_id', $programid)->count();
        if($project > 0)
        {
            $message['errors']['message'] = 'Sorry! Program cannot be deleted as it has projects associated with it.';
            return response()->json($message, 422); 
        }

        $attributes['is_deleted'] = 1;
        $attributes['deleted_by'] = auth()->user()->id;
        $attributes['deleted_at'] = (new Carbon)->toDateTimeString();
        
        $program = Program::where('id', $programid)->update($attributes);
        
        return $program;  
    }
}
