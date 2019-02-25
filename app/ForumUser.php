<?php

namespace App;

use App\UanalyzeModel;

class StockIndustry extends UanalyzeModel
{
    protected $connection = 'mysql_3';
	protected $table = 'users';
	protected $primaryKey = 'id';
    protected $fillable = ['name',
	        'email',
	        'username',
	        'password',
	        'ip',
	        'confirmed',
	        'confirmation_code',
	        'github_id',
	        'github_username',
	        'type',
	        'remember_token',
	        'bio',
	];
}
