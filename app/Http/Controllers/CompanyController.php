<?php

namespace App\Http\Controllers;

use App\Company;
use App\Policies\CompanyPolicy;
use App\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use App\Module;
use App\Action;

class CompanyController extends Controller
{
    public function __construct()
    {
       $this->middleware('can:View_Organization');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Authorize user requests to view all companies
        if(Gate::allows('View_Organization') != true)
        {
            return abort('403');
        }

        //Once authorized return all user created companies
        return Company::with('user')->where('is_deleted',0)->latest()->get();
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
        $managers = request('company_manager');
        $attributes['company_manager'] = implode(', ', array_map(function ($manager) {
            return $manager['id'];
        }, $managers));
        
        $attributes['created_by'] = auth()->user()->id;
        $attributes['modified_by'] = auth()->user()->id;
            
        //store in database
        $company = Company::create($attributes);
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
        $company = Company::with('user')->find($id);
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
        $managers = request('company_manager');
        $attributes['company_manager'] = implode(', ', array_map(function ($manager) {
            return $manager['id'];
        }, $managers));
        
        $attributes['modified_by'] = auth()->user()->id;
            
        //store in database
        $company = Company::where('id', $id)->update($attributes);
        //save in session
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
}
