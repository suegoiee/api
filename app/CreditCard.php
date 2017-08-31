<?php

namespace App;

use App\UanalyzeModel;

class CreditCard extends UanalyzeModel
{

	protected $table='credit_cards';
    protected $fillable = [
        'number','month','year','check'
    ];

    public function user(){
    	return $this->belongsTo('App\User');
    }
}
