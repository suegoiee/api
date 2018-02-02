<?php

namespace App;

use App\UanalyzeModel;

class StockProduct extends UanalyzeModel
{
	protected $table = 'company_product';
	protected $fillable = [
        'year','name','value'
    ];
    public function company()
    {
        return $this->belongsTo('App\Stock','company_id','no');
    }
}
