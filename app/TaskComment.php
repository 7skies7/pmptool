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
        return $this->hasMany(User::class, 'id','created_by')->select(['id','first_name', 'last_name']);
    }

    public function tasks()
    {
        return $this->belongsTo(Task::class, 'task_id','id')->select(['id','task_desc']);   
    }
}
