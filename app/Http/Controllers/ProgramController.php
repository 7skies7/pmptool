<?php

namespace App\Http\Controllers;

use App\Program;
use App\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;

class ProgramController extends Controller
{
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
        // return Program::with('user')->latest()->get();
        return Program::with('user')->where('is_deleted',0)->latest()->get();
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
                                        ]);
        
        $attributes['created_by'] = auth()->user()->id;
        $attributes['modified_by'] = auth()->user()->id;
        $attributes['program_start_date'] = Carbon::parse($attributes['program_start_date'])->format('Y-m-d');
        $attributes['program_end_date'] = Carbon::parse($attributes['program_end_date'])->format('Y-m-d');
        $managers = request('program_manager');
        $attributes['program_manager'] = implode(', ', array_map(function ($manager) {
            return $manager['id'];
        }, $managers));
        
        //store in database
        $program = Program::create($attributes);
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
        $program = Program::with('user')->find($id);
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
                                        ]);

        $attributes['program_start_date'] = Carbon::parse($attributes['program_start_date'])->format('Y-m-d');
        $attributes['program_end_date'] = Carbon::parse($attributes['program_end_date'])->format('Y-m-d');
        $managers = request('program_manager');

        $attributes['program_manager'] = implode(', ', array_map(function ($manager) {
            return $manager['id'];
        }, $managers));
        
        $attributes['modified_by'] = auth()->user()->id;
            
        //store in database
        $program = Program::where('id', $id)->update($attributes);
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
        
        $attributes['is_deleted'] = 1;
        $attributes['deleted_by'] = auth()->user()->id;
        $attributes['deleted_at'] = (new Carbon)->toDateTimeString();
        
        $program = Program::where('id', $programid)->update($attributes);
        
        return $program;  
    }
}
