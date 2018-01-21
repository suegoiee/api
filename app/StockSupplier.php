<?php

namespace App;

use App\UanalyzeModel;

class StockSupplier extends UanalyzeModel
{
	protected $table = 'company_supplier';
	protected $fillable = [
        'name','value'
    ];
    public function company()
    {
        return $this->belongsTo('App\Stock','company_id','no');
    }
}
