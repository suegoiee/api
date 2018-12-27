<?php

namespace App;

use App\UanalyzeModel;
class Analyst_grant extends UanalyzeModel
{
	protected $table = 'analyst_grants';
    protected $fillable = [
        'statement_no', 'year_month','price','handle_fee','platform_fee','income_tax','second_generation_nhi','interbank_remittance_fee','ratio'
    ];
    public function analyst()
    {
    	return $this->belongsTo('App\Analyst');
    }
    public function details()
    {
        return $this->hasMany('App\Analyst_grant_detail');
    }
}
