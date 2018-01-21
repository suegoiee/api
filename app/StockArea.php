<?php

namespace App;

use App\UanalyzeModel;

class StockArea extends UanalyzeModel
{
	protected $table = 'company_area';
	protected $fillable = [
        'name','value'
    ];
    public function company()
    {
        return $this->belongsTo('App\Stock','company_id','no');
    }
}
