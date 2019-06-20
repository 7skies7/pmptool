<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $table = 'project';

    protected $casts = [
        'project_status' => 'integer',
        'project_budget' => 'integer',
    ];
	/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'project_name', 'project_start_date', 'project_end_date', 'project_desc','created_by','created_at','modified_at','modified_by','project_status','project_budget'
    ];

	public function user()
    {
        return $this->hasMany(User::class, 'id','project_manager')->select(['id','first_name','last_name']);
    }

    public function status()
    {
        return $this->belongsTo(Status::class, 'project_status','id')->select(['id','status_name']);
    }

    public function managers()
    {
        return $this->hasMany(ProjectManager::class);
    }
}
