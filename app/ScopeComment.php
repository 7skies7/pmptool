<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ScopeComment extends Model
{
    protected $table = 'scope_comments';

    public $timestamps = false;
	/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public function documents()
    {
        return $this->hasMany(Document::class, 'id','document_id')->select(['id','file_name']);
    }

    public function users()
    {
        return $this->hasMany(User::class, 'id','created_by')->select(['id','first_name', 'last_name']);
    }
}
