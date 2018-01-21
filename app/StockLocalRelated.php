<?php

namespace App;

use App\UanalyzeModel;

class StockLocalRelated extends UanalyzeModel
{
	protected $table = 'company_local_related';
	protected $fillable = [
        'name','value'
    ];
    public function company()
    {
        return $this->belongsTo('App\Stock','company_id','no');
    }
}
