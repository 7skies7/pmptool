<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use DB;

class User extends Authenticatable
{
    use Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id','first_name', 'last_name', 'email', 'password', 'role_id', 'designation_id', 'alternate_email', 'password',
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
            UserRole::class, // The intermediate table that connects the Company with the User.
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
            UserCompany::class, // The intermediate table that connects the Company with the User.
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

    public function timesheet()
    {
        return DB::select("select TIME_FORMAT(SEC_TO_TIME(SUM(TIME_TO_SEC(task_hours))), '%H:%i') as hours, date_format(tc.created_at, '%Y-%m-%d') as day, group_concat(concat(tk.task_title,'!%',task_comment,'!%',TIME_FORMAT(task_hours, '%H:%i')) SEPARATOR '!;') as task_details from task_comments tc inner join tasks tk on tk.id = tc.task_id where tc.created_by = ".auth()->user()->id." and tk.company_id = ".session('company_id')." group by date_format(tc.created_at, '%Y-%m-%d')
            ");
    }

    public function timecard()
    {
        return DB::select("select log_in_date, DATE_FORMAT(log_in_time, '%H:%i') as log_in_time, DATE_FORMAT(log_out_time, '%H:%i') as log_out_time, TIME_FORMAT(TIMEDIFF(log_out_time, log_in_time), '%H:%i') as total_time from timecard where user_id = ".auth()->user()->id." group by log_in_date;");
    }

    public static function fetchCompanyUsers()
    {
        $companies = UserCompany::where('user_id', auth()->user()->id)->where('company_id', session('company_id'))->pluck('company_id');
        $userid = UserCompany::whereIn('company_id', $companies)->pluck('user_id');
        return User::with('roles')->with('companies')->whereIn('id', $userid)->where('is_deleted',0)->get();

    }

}
