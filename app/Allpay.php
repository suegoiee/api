<?php

namespace App;

use App\UanalyzeModel;

class Allpay extends UanalyzeModel
{
    protected $fillable = [
        'order_id','MerchantTradeNo'
    ];
    public function order(){
        return $this->belongsTo('App\Order');
    }
    public function feedbacks(){
        return $this->hasMany('App\Allpay_feedback');
    }
}
