<?php

namespace App;

use App\UanalyzeModel;
class Product_price extends UanalyzeModel
{
    protected $fillable = [
        'price','expiration','active', 'introduction','freecourses', 'product_id', 'name'
    ];
    protected $hidden = [
        'created_at','updated_at','active'
    ];
    public function product(){
    	return $this->belongsTo('App\Product');
    }
    public function included_product(){
    	return $this->hasMany('App\Product_solutions', 'product_prices_id');
    }
}
