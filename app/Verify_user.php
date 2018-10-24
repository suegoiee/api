<?php

namespace App;

use App\UanalyzeModel;

class Verify_user extends UanalyzeModel
{
	protected $table = 'verify_users';
    protected $fillable = [
        'email', 'token'
    ];
}
