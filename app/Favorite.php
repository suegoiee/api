<?php

namespace App;

use App\UanalyzeModel;
class Favorite extends UanalyzeModel
{
    protected $fillable = [
        'stock_name','stock_code'
    ];

    protected $hidden = [
        'user_id', 'created_at', 'updated_at'
    ];
    public function user(){
    	return $this->belongsTo('App\User');
    }
    public function company(){
    	return $this->hasOne('App\Stock', 'stock_code', 'stock_code');
    }
}
