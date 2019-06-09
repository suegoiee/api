<?php

namespace App;

use App\UanalyzeModel;

class event extends UanalyzeModel
{
    protected $fillable = [
        'name','status','type','started_at','ended_at'
    ];

    public function condition_products(){
    	return $this->belongsToMany('App\Product', 'condition_product')->withPivot('discount','quantity');
    }

     public function products(){
    	return $this->belongsToMany('App\Product')->withPivot('discount','quantity');
    }
}
