<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Userstory extends Model
{
    protected $table = 'userstory';

    public $timestamps = false;
	/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public function status()
    {
        return $this->belongsTo(Status::class, 'userstory_status','id')->select(['id','status_name']);
    }

    public function priority()
    {
        return $this->belongsTo(Priority::class, 'userstory_priority','id')->select(['id','priority_type', 'priority_color']);
    }

    public function approveddocument()
    {
        return $this->hasOne(Document::class, 'id','approved_document')->select(['id','file_name']);
    }

    public function scope()
    {
        return $this->belongsToMany(Scope::class, 'cr_id', 'id');   
    }

    public function project()
    {
        return $this->belongsToMany(Project::class, 'id','project_id')->select(['project_name']);
    }
}
