<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CompanyManager extends Model
{
    protected $table = 'manager_company';

    public $timestamps = false;
	/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['company_id','user_id'];

    

}
