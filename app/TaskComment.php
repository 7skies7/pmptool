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
    protected $dates = ['created_at'];

    public function users()
    {
        return $this->belongsTo(User::class, 'created_by','id')->select(['id','first_name', 'last_name']);
    }

    public function tasks()
    {
        return $this->belongsTo(Task::class, 'task_id','id')->select(['id','task_desc']);   
    }

    public function project()
    {
        return $this->hasManyThrough(
            Project::class,          // The model to access to
            Task::class, // The intermediate table that connects the Company with the User.
            'id',                 // The column of the intermediate table that connects to this model by its ID.
            'id',              // The column of the intermediate table that connects the Company by its ID.
            'task_id',                      // The column that connects this model with the intermediate model table.
            'project_id'               // The column of the user table that ties it to the Podcast.
        )->select(['project_name']);
    }
}
