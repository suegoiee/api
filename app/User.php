<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
//use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Notifications\ResetPassword as ResetPasswordNotification;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use App\Services\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use App\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User  extends Model implements
    AuthenticatableContract,
    AuthorizableContract,
    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword, HasApiTokens, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email', 'password'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    protected $appends = [ 'avatar' ];

    public function sendPasswordResetNotification($token, $redirect)
    {
        $this->notify(new ResetPasswordNotification($token, $redirect));
    }

    public function profile(){
        return $this->hasOne('App\Profile');
    }

    public function avatars()
    {
        return $this->morphMany('App\Avatar', 'imageable');
    }
    public function getAvatarAttribute()
    {
        return $this->avatars()->first();
    }
    public function creditCards()
    {
        return $this->hasMany('App\CreditCard');
    }
    public function products(){
        return $this->belongsToMany('App\Product')->withPivot('installed', 'deadline')->withTimestamps();
    }
    public function laboratories()
    {
        return $this->hasMany('App\Laboratory');
    }
    public function favorites(){
        return $this->hasMany('App\Favorite');
    }
    public function orders()
    {
        return $this->hasMany('App\Order');
    }

}
