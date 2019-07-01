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

    public function program()
    {
        return $this->hasMany(Program::class, 'created_by');
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

    
}
