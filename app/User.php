<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Notifications\ResetPassword as ResetPasswordNotification;

class User  extends Authenticatable
{
    use HasApiTokens, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'username', 'confirmed', 'bio', 'confirmation_code', 'email', 'password', 'is_socialite', 'version', 'mail_verified_at', 'phone', 'subscription','set_password'
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

    public function socialite(){
        return $this->hasOne('App\Socialite');
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
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
        return $this->belongsToMany('App\Product')->withPivot('installed', 'deadline','sort')->withTimestamps();
    }
    public function laboratories()
    {
        return $this->hasMany('App\Laboratory');
    }

    public function master_laboratories()
    {
        return $this->belongsToMany('App\Laboratory')->withPivot('sort')->withTimestamps();
    }

    public function favorites(){
        return $this->hasMany('App\Favorite');
    }
    public function orders()
    {
        return $this->hasMany('App\Order');
    }
    public function promocodes()
    {
        return $this->hasMany('App\Promocode');
    }
    public function permissions()
    {
        return $this->hasMany('App\Permission');
    }
}
