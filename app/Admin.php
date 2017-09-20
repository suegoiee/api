<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    protected $table = 'admins';
    protected $fillable = [
        'name', 'password',
    ];
    protected $hidden = [
        'password', 'remember_token',
    ];
}
