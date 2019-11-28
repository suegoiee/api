<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    const REGULAR = 1;
    const MANAGER = 5;
    const SUPER = 10;

    protected $table = 'admins';
    protected $fillable = [
        'name', 'password', 'auth'
    ];
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function isSuper(): bool
    {
        return $this->auth === self::SUPER;
    }

    public function isManager(): bool
    {
        return $this->auth === self::MANAGER;
    }

    public function isRegular(): bool
    {
        return $this->auth === self::REGULAR;
    }

    public function hasPermission(): bool
    {
        return $this->auth === self::REGULAR;
    }
    
    function role(){
        return $this->hasOne(Role::class, 'id', 'auth');
    }
}
