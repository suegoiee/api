<?php

namespace App;

use App\UanalyzeModel;

class StockProduct extends UanalyzeModel
{
	protected $table = 'company_product';
	protected $fillable = [
        'name','value'
    ];
    public function company()
    {
        return $this->belongsTo('App\Stock','company_id','no');
    }
}
