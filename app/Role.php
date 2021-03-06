<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = "roles";

    public function user()
    {
        return $this->belongsToMany(User::class);
    }

    public function module()
    {
        return $this->belongsToMany(Module::class);
    }


    
}
