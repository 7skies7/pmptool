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

    public function userstory_id()
    {
        return $this->belongsTo(Userstory::class, 'userstory_id','id')->select(['id','priority_type', 'priority_color']);
    }

   
}
