<?php
namespace App;
trait HasRoles
{
    /**
     * A user may have multiple roles.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }
    /**
     * Assign the given role to the user.
     *
     * @param  string $role
     * @return mixed
     */
    public function assignRole($role)
    {
        return $this->roles()->save(
            Role::whereName($role)->firstOrFail()
        );
    }
    /**
     * Determine if the user has the given role.
     *
     * @param  mixed $role
     * @return boolean
     */
    public function hasRole($role)
    {
        
        if (is_string($role)) {
            return $this->roles->contains('role_id', $role);
        }

        return !! $role->intersect($this->roles)->count();
    }
    /**
     * Determine if the user may perform the given permission.
     *
     * @param  Permission $permission
     * @return boolean
     */
    public function hasPermission(Module $module, Action $action)
    {
        // loop over user roles
        // 
        // $this->roles();
        // dd($this->roles);
        foreach ($this->roles as $key => $role) {
            
            $access = Acl::where('role_id',$role->id)->where('module_id',$module->id)->where('action_id',$action->id)->pluck('access_status');
            
            return $access[0];
        }
        


        //return $this->hasRole($module->roles);
    }
}