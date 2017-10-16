<?php

namespace App;

use App\UanalyzeModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product_faq extends UanalyzeModel
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    
    protected $fillable = [
        'question','answer'
    ];
    protected $hidden = [
        'product_id','created_at','updated_at','deleted_at'
    ];
    public function product(){
    	return $this->belongsTo('App\Product');
    }
}
