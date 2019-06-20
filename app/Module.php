<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    //
    protected $table = "modules";

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'acls', 'module_id', 'role_id');
    }

    public function actions()
    {
        return $this->belongsToMany(Action::class, 'acls', 'module_id', 'action_id');
    }
}
