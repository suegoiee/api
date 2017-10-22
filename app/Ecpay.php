<?php

namespace App;

use App\UanalyzeModel;

class Ecpay extends UanalyzeModel
{
    protected $fillable = [
        'order_id','MerchantTradeNo'
    ];
    protected $hidden = [
    ];
    public function order(){
        return $this->belongsTo('App\Order');
    }
    public function feedbacks(){
        return $this->hasMany('App\Ecpay_feedback');
    }
}
