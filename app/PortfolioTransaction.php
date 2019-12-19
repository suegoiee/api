<?php

namespace App;

use App\UanalyzeModel;

class PortfolioTransaction extends UanalyzeModel
{
	protected $fillable = [
        'portfolio_id', 'price', 'quantity'
    ];
    public function portfolio()
    {
        return $this->belongsTo('App\Portfolio');
    }
}
