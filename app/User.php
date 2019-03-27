<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'street', 'city', 'state', 'zip', 'commutesmart_frequency', 'company_id', 'is_captain'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function trips()
    {
    	return $this->hasMany(Trip::class);
    }

    public function company()
    {
    	return $this->belongsTo(Company::class);
    }
}
