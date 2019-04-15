<?php

namespace App;

use App\UanalyzeModel;

class Referrer_grant_other extends UanalyzeModel
{
	protected $table = 'referrer_grant_others';
    protected $fillable = [
        'category','name','amount'
    ];
    public function grant()
    {
    	return $this->belongsTo('App\Referrer_grant.php');
    }
}
