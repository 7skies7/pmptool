<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProjectManager extends Model
{
    protected $table = 'manager_project';

    public $timestamps = false;
	/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['project_id','user_id'];

}
