<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProjectStakeholder extends Model
{
    protected $table = 'stakeholder_project';

    public $timestamps = false;
	/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

}
