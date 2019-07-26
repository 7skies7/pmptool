<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name', 'email', 'password', 'role_id', 'designation_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function roles()
    {
        return $this->hasManyThrough(
            Role::class,          // The model to access to
            Userrole::class, // The intermediate table that connects the Company with the User.
            'user_id',                 // The column of the intermediate table that connects to this model by its ID.
            'id',              // The column of the intermediate table that connects the Company by its ID.
            'id',                      // The column that connects this model with the intermediate model table.
            'role_id'               // The column of the user table that ties it to the Podcast.
        )->select(['id','role_title','rank'])->orderBy('rank');
    }

    public function companies()
    {
        return $this->hasManyThrough(
            Company::class,          // The model to access to
            Usercompany::class, // The intermediate table that connects the Company with the User.
            'user_id',                 // The column of the intermediate table that connects to this model by its ID.
            'id',              // The column of the intermediate table that connects the Company by its ID.
            'id',                      // The column that connects this model with the intermediate model table.
            'company_id'               // The column of the user table that ties it to the Podcast.
        )->select(['id','company_name']);
    }

    public function company()
    {
        return $this->belongsToMany(Company::class, 'company_manager');
    }

    /**
     * Get initals of the full name from auth user name and convert to uppercase
     *
     * @var array
     */
    public function initials(){
        $words[0] = $this->first_name;
        $words[1] = $this->last_name;
        $initials = null;
        foreach ($words as $w) {
            $initials .= $w[0];
        }
        return strtoupper($initials);
    }

    public function getFullnameAttribute()
    {
      return "{$this->first_name} {$this->last_name}";
    }

    public static function isRole($role)
    {
        $roles = auth()->user()->roles;
        if(count($roles) >= 1)
        {
            if($roles->contains('id', $role))
            {
                return true;
            }
        }

        return false;
    }

}
