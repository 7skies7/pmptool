<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

class Company extends Model
{
    //
    protected $table = 'companies';
	/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public function user()
	{
		return $this->hasMany(User::class, 'id','company_manager')->select(['id','first_name','last_name']);
	}
}
