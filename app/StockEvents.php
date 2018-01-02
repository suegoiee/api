<?php

namespace App;

use App\UanalyzeModel;

class StockEvents extends UanalyzeModel
{
	protected $table = 'company_events';
	protected $fillable = [
        'year','content'
    ];
    public function company()
    {
        return $this->belongsTo('App\Stock','company_id','no');
    }
}
