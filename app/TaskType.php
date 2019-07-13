<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TaskType extends Model
{
    //
    protected $table = "task_type";

    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];
}
