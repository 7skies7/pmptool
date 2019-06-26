<?php

namespace App\Http\Controllers;

use App\Role;
use App\Module;
use App\Action;
use App\Acl;
use DB;
use App\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AclController extends Controller
{
    /**
     * Display a listing of all roles.
     *
     * @return \Illuminate\Http\Response
     */
    public function roles()
    {
        // return Program::with('user')->latest()->get();
        return Role::select('id', 'role_title')->get();
    }

    /**
     * Display a listing of all modules.
     *
     * @return \Illuminate\Http\Response
     */
    public function modules()
    {
        return Module::select('id','module_name')->get();
    }

    /**
     * Display a listing of all actions.
     *
     * @return \Illuminate\Http\Response
     */
    public function actions()
    {
        return Action::select('id','action_name')->get();
    }

    /**
     * Fetach all modules and actions access list related to role
     * @param $roleid
     * @return \Illuminate\Http\Response
     */
    public function access($roleid)
    {
        return (new Acl)->getAccessData($roleid);
    }

    /**
     * Update access for the specific role
     * @param $roleid
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $isRoleAccessExist = Acl::where('module_id', request('module_id'))->where('action_id', request('action_id'))->where('role_id', request('role_id'))->get()->toArray();
        
        if(count($isRoleAccessExist) > 0)
        {
            $attributes['access_status'] = request('access_status');
            
            Acl::where('id', $isRoleAccessExist[0]['id'])->update($attributes);
        }
        return (new Acl)->getAccessData(request('role_id'));
    }

    /**
     * Populate access for all roles
     * @param $roleid
     * @return \Illuminate\Http\Response
     */
    public function fakeaccess()
    {
        $modules = Module::select('id')->get();
        $actions = Action::select('id')->get();
        $roles = Role::select('id')->get();

        foreach($roles as $role) {
            $attributes['role_id'] = $role->id;
            foreach ($modules as $module) {
                // $attributes['module_id'] = 9;
                $attributes['module_id'] = $module->id;

                foreach ($actions as $action) {
                    $attributes['action_id'] = $action->id;
                    $attributes['created_by'] = 1;
                    $attributes['modified_by'] = 1;
                    // dd($attributes);
                    Acl::create($attributes);
                    
                }
            }
        }
        dd('Access updated');
        
    }
    
}
