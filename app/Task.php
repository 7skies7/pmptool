<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $table = 'tasks';

    public $timestamps = false;
	/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public function status()
    {
        return $this->belongsTo(Status::class, 'task_status','id')->select(['id','status_name']);
    }

    public function priority()
    {
        return $this->belongsTo(Priority::class, 'task_priority','id')->select(['id','priority_type', 'priority_color']);
    }

    public function userstory()
    {
        return $this->belongsTo(Userstory::class, 'userstory_id','id')->select(['id','userstory_desc', 'userstory_id']);
    }

    public function assignee()
    {
        return $this->belongsTo(User::class, 'task_assignee','id')->select(['id','first_name', 'last_name']);
    }

    public function tasktype()
    {
        return $this->belongsTo(TaskType::class, 'task_type','id')->select(['id','type']);
    }


    public function subtasks()
    {
        return $this->hasMany(static::class, 'parent_id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id','id')->select(['id','project_name']);
    }

   
}
