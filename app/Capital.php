<?php

namespace App;

use App\UanalyzeModel;

class Capital extends UanalyzeModel
{
    protected $fillable = [
        'order_id', 'CustID', 'AwardName', 'Points', 'VendorName', 'PayAmt', 'StatusCode', 'Massage'
    ];
    protected $hidden = [
    ];
    public function order(){
        return $this->belongsTo('App\Order');
    }
}
