<?php

namespace App;

use App\UanalyzeModel;

class StockCustomer extends UanalyzeModel
{
	protected $table = 'company_customer';
	protected $fillable = [
        'name','value'
    ];
    public function company()
    {
        return $this->belongsTo('App\Stock','company_id','no');
    }
}
