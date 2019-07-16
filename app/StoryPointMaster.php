<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StoryPointMaster extends Model
{
    protected $table = 'story_point_master';

    public $timestamps = false;

	/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public static function pointtohours($point)
    {
        return $point * (StoryPointMaster::find(1))->hours;
    }
}
