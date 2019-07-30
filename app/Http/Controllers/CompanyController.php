<?php

namespace App\Http\Controllers;

use App\Company;
use App\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use App\CompanyManager;
use App\UserRole;
use App\UserCompany;

class CompanyController extends Controller
{
    protected $company_role_id = 1;

    public function __construct()
    {
       // $this->middleware('can:View_Organization');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Authorize user's request to view all companies
        if(Gate::allows('View_Organization') != true)
        {
            return abort('403');
        }

        return $this->fetchAllCompanies();
        
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
        // Authorize user requests to store new company details
        if(Gate::allows('Create_Organization') != true)
        {
            return abort('403');
        }
        //validate
        $attributes = request()->validate(['company_name'=> 'required', 
                                            'company_desc' => 'required',
                                            'company_manager' => 'required',
                                        ]);
        
        $attributes['created_by'] = auth()->user()->id;
        $attributes['modified_by'] = auth()->user()->id;
            
        //store in database
        unset($attributes['company_manager']);
        $company = Company::create($attributes);

        foreach(request('company_manager') as $manager)
        {
            $managerArr['company_id'] =  $company->id;
            $managerArr['user_id'] = $manager['id'];
            CompanyManager::create($managerArr);
            //Assign Organization Manager role to all the company managers selected
            $roleArr['user_id'] = $manager['id'];
            $roleArr['role_id'] = $this->company_role_id;
            UserRole::firstOrCreate($roleArr,$roleArr);
        }


        //save in session
        return $company;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function show(Company $company)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $company = Company::with('managers')->find($id);
        return json_encode($company);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Authorize user requests to update resource details
        if(Gate::allows('Update_Organization') != true)
        {
            return abort('403');
        }

        //validate
        $attributes = request()->validate(['company_name'=> 'required', 
                                            'company_desc' => 'required',
                                            'company_manager' => 'required',
                                        ]);
        $attributes['modified_by'] = auth()->user()->id;
            
        //store in database
        unset($attributes['company_manager']);
        $company = Company::where('id', $id)->update($attributes);
        //save in session

        //Delete previous company records
        CompanyManager::where('company_id',$id)->delete();
        //Delete previous company role for userrecords
        //UserRole::where('role_id',$this->company_role_id)->delete();
        
        foreach(request('company_manager') as $manager)
        {
            $managerArr['company_id'] =  $id;
            $managerArr['user_id'] = $manager['id'];
            CompanyManager::create($managerArr);
            //Assign Organization Manager role to all the company managers selected
            $roleArr['user_id'] = $manager['id'];
            $roleArr['role_id'] = $this->company_role_id;
            UserRole::firstOrCreate($roleArr,$roleArr);
        }
        return $company;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function destroy($companyid)
    {
        // Authorize user requests to delete resource
        if(Gate::allows('Delete_Organization') != true)
        {
            return abort('403');
        }

        $attributes['is_deleted'] = 1;
        $attributes['deleted_by'] = auth()->user()->id;
        $attributes['deleted_at'] = (new Carbon)->toDateTimeString();
        
        $company = Company::where('id', $companyid)->update($attributes);
        return $company;   
    }

    public function fetchAllCompanies()
    {
        if(User::isRole(1) || User::isRole(2))
        {
            return Company::with('managers')->where('is_deleted',0)->latest()->get();
        }

        //Once authorized return all user created companies
        if(User::isRole(6) || User::isRole(7) || User::isRole(5))
        {
            $companies = UserCompany::where('user_id', auth()->user()->id)->pluck('company_id');
            return Company::with('managers')->whereIn('id', $companies)->where('is_deleted',0)->latest()->get();
            
        }
    }
}
