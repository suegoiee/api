<?php

namespace App;

use App\UanalyzeModel;

class Analyst_grant_detail extends UanalyzeModel
{
	protected $table = 'analyst_grant_details';
    protected $fillable = [
        'category','name','amount'
    ];
    public function grant()
    {
    	return $this->belongsTo('App\Analyst_grant.php');
    }
}
