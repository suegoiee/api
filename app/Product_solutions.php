<?php

namespace App;

use App\UanalyzeModel;
class Product_solutions extends UanalyzeModel
{
    protected $fillable = [
        'freecourses', 'solution_product_id', 'product_prices_id'
    ];
    protected $hidden = [
        'created_at','updated_at'
    ];
    public function product_price(){
    	return $this->belongsTo('App\Product_price');
    }
}
