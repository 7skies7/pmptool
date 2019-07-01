<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    protected $table = 'program';
	/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'program_name', 'program_start_date', 'program_end_date', 'program_desc','created_by','created_at','modified_at','modified_by','program_manager'
    ];

	public function user()
    {
        return $this->hasMany(User::class, 'id','program_manager')->select(['id','first_name','last_name']);
    }
    public function managers()
    {
        return $this->hasManyThrough(
            User::class,          // The model to access to
            ProgramManager::class, // The intermediate table that connects the Company with the User.
            'program_id',                 // The column of the intermediate table that connects to this model by its ID.
            'id',              // The column of the intermediate table that connects the Company by its ID.
            'id',                      // The column that connects this model with the intermediate model table.
            'user_id'               // The column of the user table that ties it to the Podcast.
        )->select(['id','first_name','last_name']);
    }

}
