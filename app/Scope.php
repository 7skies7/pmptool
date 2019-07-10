<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Scope extends Model
{
    protected $table = 'scope';

    public $timestamps = false;
	/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public function status()
    {
        return $this->belongsTo(Status::class, 'crd_status','id')->select(['id','status_name']);
    }

    public function approveddocument()
    {
        return $this->hasOne(Document::class, 'id','approved_document')->select(['id','file_name']);
    }

    public function userstories()
    {
        return $this->hasMany(Userstory::class, 'cr_id', 'id');   
    }
}
