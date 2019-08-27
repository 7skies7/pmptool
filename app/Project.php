<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

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
        'id','project_name', 'project_start_date', 'project_end_date', 'project_desc','created_by','created_at','modified_at','modified_by','project_status','project_budget','program_id','company_id'
    ];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('company', function (Builder $builder) {
            $builder->where('company_id', '=', session('company_id'));
        });
    }

	public function user()
    {
        return $this->hasMany(User::class, 'id','project_manager')->select(['id','first_name','last_name']);
    }

    public function status()
    {
        return $this->belongsTo(Status::class, 'project_status','id')->select(['id','status_name']);
    }

    public function program()
    {
        return $this->belongsTo(Program::class, 'program_id','id')->select(['id','program_name']);
    }

    public function managers()
    {
        return $this->hasManyThrough(
            User::class,          // The model to access to
            ProjectManager::class, // The intermediate table that connects the Company with the User.
            'project_id',                 // The column of the intermediate table that connects to this model by its ID.
            'id',              // The column of the intermediate table that connects the Company by its ID.
            'id',                      // The column that connects this model with the intermediate model table.
            'user_id'               // The column of the user table that ties it to the Podcast.
        )->select(['id','first_name','last_name']);
    }

    public function stakeholders()
    {
        return $this->hasManyThrough(
            User::class,          // The model to access to
            ProjectStakeholder::class, // The intermediate table that connects the Company with the User.
            'project_id',                 // The column of the intermediate table that connects to this model by its ID.
            'id',              // The column of the intermediate table that connects the Company by its ID.
            'id',                      // The column that connects this model with the intermediate model table.
            'user_id'               // The column of the user table that ties it to the Podcast.
        )->select(['id','first_name','last_name']);
    }
}
