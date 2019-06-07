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
}
