<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    protected $table = 'project_status';
	/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

}
