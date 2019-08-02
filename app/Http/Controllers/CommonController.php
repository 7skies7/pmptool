<?php

namespace App\Http\Controllers;

use App\User;
use App\Status;
use App\Priority;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;
use App\Program;
use DB;
use App\UserCompany;
use App\ProgramManager;

class CommonController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getStatus()
    {
        if(User::isRole(1) || User::isRole(2) || User::isRole(5) || User::isRole(6))
        {
            return Status::select('id',"status_name")->get();
        }
        return  Status::select('id',"status_name")->where('id','!=',2)->get();
    }

    public function getPriority()
    {
        return Priority::select('id',"priority_type")->get();
    }

    public function getResources()
    {
        // If role is Super Admin / Admin, show list of all users
        if(User::isRole(1) || User::isRole(2))
        {
            return User::select('id',DB::raw("CONCAT(users.first_name,' ',users.last_name) as name", "email"))->get();    
        }
        
        //Once authorized return all users associated to the program
        if(User::isRole(6) || User::isRole(7) || User::isRole(5))
        {
            $companies = UserCompany::where('user_id', auth()->user()->id)->pluck('company_id');
            $userid = Usercompany::whereIn('company_id', $companies)->pluck('user_id');
            return User::select('id',DB::raw("CONCAT(users.first_name,' ',users.last_name) as name", "email"))->whereIn('id', $userid)->get();    

        }
    }

    public function getPrograms()
    {
         // If role is Super Admin / Admin, show list of all users
        if(User::isRole(1) || User::isRole(2))
        {
            return Program::select('id',"program_name")->get();
        }

        if(User::isRole(5))
        {
            $companies = UserCompany::where('user_id', auth()->user()->id)->pluck('company_id');  
            return Program::select('id',"program_name")->whereIn('company_id', $companies)->get();
        }

        // If role is Organization Manager, show list of all programs within that organization
        if(User::isRole(6) || User::isRole(7))
        {
            $programs = ProgramManager::where('user_id', auth()->user()->id)->pluck('program_id');
            return Program::with('managers')->with('company')->whereIn('id', $programs)->where('is_deleted',0)->latest()->get();
        }
        
    
    }
}