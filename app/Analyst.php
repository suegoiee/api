<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Analyst extends Authenticatable
{
	use SoftDeletes;
    protected $table = 'analysts';
    protected $fillable = [
        'email', 'password','name','no','ratio'
    ];
    protected $hidden = [
        'password', 'remember_token',
    ];
    public function grants()
    {
        return $this->hasMany('App\Analyst_grant');
    }
    public function products()
    {
    	return $this->belongsToMany('App\Product');
    }
}
