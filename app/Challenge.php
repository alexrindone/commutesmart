<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Challenge extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 
        'slug', 
        'start_date', 
        'end_date', 
        'type', 
        'image_url', 
        'level_one_icon', 
        'level_one_label', 
        'level_two_icon', 
        'level_two_label', 
        'level_three_icon', 
        'level_three_label', 
        'level_four_icon', 
        'level_four_label'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [

    ];

    public function trips()
    {
    	return $this->hasMany(Trip::class);
    }
}
