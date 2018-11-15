<?php

namespace App;

use App\UanalyzeModel;
class Product_price extends UanalyzeModel
{
    protected $fillable = [
        'price','expiration','active'
    ];
    protected $hidden = [
        'id','product_id','created_at','updated_at','active'
    ];
    public function product(){
    	return $this->belongsTo('App\Product');
    }
}
