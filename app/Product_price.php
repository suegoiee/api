<?php

namespace App;

use App\UanalyzeModel;
class Product_price extends UanalyzeModel
{
    protected $fillable = [
        'price','expiration'
    ];
    protected $hidden = [
        'created_at','updated_at'
    ];
    public function product(){
    	return $this->belongsTo('App\Product');
    }
}
