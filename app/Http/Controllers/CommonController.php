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
        return User::select('id',DB::raw("CONCAT(users.first_name,' ',users.last_name) as name", "email"))->get();    
    }

    public function getPrograms()
    {
         // If role is Super Admin / Admin, show list of all users
        if(User::isRole(1) || User::isRole(2))
        {
            return Program::select('id',"program_name")->get();
        }

        //Once authorized return all users associated to the program
        if(User::isRole(6) || User::isRole(7))
        {
            return User::fetchCompanyUsers();
        }
        
    
    }
}