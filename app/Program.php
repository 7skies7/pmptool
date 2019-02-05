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
        'program_name', 'program_start_date', 'program_end_date', 'program_desc','user_id'
    ];

	public function user()
	{
		return $this->belongsTo(User::class)->select(['id','name']);
	}
}
