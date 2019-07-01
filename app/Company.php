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


    public function managers()
    {
        return $this->hasManyThrough(
            User::class,          // The model to access to
            CompanyManager::class, // The intermediate table that connects the Company with the User.
            'company_id',                 // The column of the intermediate table that connects to this model by its ID.
            'id',              // The column of the intermediate table that connects the Company by its ID.
            'id',                      // The column that connects this model with the intermediate model table.
            'user_id'               // The column of the user table that ties it to the Podcast.
        )->select(['id','first_name','last_name']);
    }
    
}
