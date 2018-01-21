<?php

namespace App;

use App\UanalyzeModel;

class StockForeignRelated extends UanalyzeModel
{
	protected $table = 'company_foreign_related';
	protected $fillable = [
        'name','value'
    ];
    public function company()
    {
        return $this->belongsTo('App\Stock','company_id','no');
    }
}
