<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Referrer extends Authenticatable
{
	use SoftDeletes;
    protected $table = 'referrers';
    protected $fillable = [
        'email', 'password', 'name', 'no', 'code', 'divided', 'bank_code', 'bank_branch', 'bank_name', 'bank_account'
    ];
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function grants()
    {
        return $this->hasMany('App\Referrer_grant');
    }
    public function products()
    {
    	return $this->belongsToMany('App\Product')->withPivot(['divided']);
    }
}
