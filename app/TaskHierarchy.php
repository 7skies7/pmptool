<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TaskHierarchy extends Model
{
    //
    protected $table = "task_heirarchy";

    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];
}
