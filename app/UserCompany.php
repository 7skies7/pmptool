<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserCompany extends Model
{
    protected $table = 'company_user';

    public $timestamps = false;
	/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['company_id','user_id'];

    

}
