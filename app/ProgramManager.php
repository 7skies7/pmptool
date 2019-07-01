<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProgramManager extends Model
{
    protected $table = 'manager_program';

    public $timestamps = false;
	/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['program_id','user_id'];

    

}
