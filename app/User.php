<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Notifications\ResetPassword as ResetPasswordNotification;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    public function profile(){
        return $this->hasOne('App\Profile');
    }

    public function avatar()
    {
        return $this->morphMany('App\Avatar', 'imageable');
    }
    public function creditCard()
    {
        return $this->hasMany('App\CreditCard');
    }
    public function products(){
        return $this->belongsToMany('App\Product')->withPivot('title', 'deadline')->withTimestamps();
    }
    public function laboratories()
    {
        return $this->hasMany('App\Laboratory');
    }
    public function favorites(){
        return $this->belongsToMany('App\Stock','favorites')->withTimestamps();
    }
    public function orders()
    {
        return $this->hasMany('App\Order');
    }

}
