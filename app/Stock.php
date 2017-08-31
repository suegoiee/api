<?php

namespace App;

use App\UanalyzeModel;

class Stock extends UanalyzeModel
{
    protected $fillable = [
        'name','abbrev','code'
    ];

    public function users(){
    	return $this->belongsToMany('App\User','favorites')->withTimestamps();
    }

}
