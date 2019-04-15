<?php

namespace App;

use App\UanalyzeModel;
class Referrer_grant extends UanalyzeModel
{
	protected $table = 'referrer_grants';
    protected $fillable = [
        'statement_no', 'year_month','price','handle_fee','platform_fee','income_tax','second_generation_nhi','interbank_remittance_fee','divided'
    ];
    public function referrer()
    {
    	return $this->belongsTo('App\Referrer');
    }
    public function others()
    {
        return $this->hasMany('App\Referrer_grant_other');
    }
}
