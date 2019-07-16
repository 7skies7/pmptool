<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TaskComment extends Model
{
    protected $table = 'task_comments';

    public $timestamps = false;
	/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];


    public function users()
    {
        return $this->hasMany(User::class, 'id','created_by')->select(['id','first_name', 'last_name']);
    }
}
