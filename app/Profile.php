<?php

namespace App;

use App\UanalyzeModel;

class Profile extends UanalyzeModel
{
	protected $fillable=['user_id','nickname','name','sex','address','birthday','phone','company_id','invoice_title'];
	protected $hidden = [
        'id','user_id',
    ];
    
	public function user(){
		return $this->belongsTo('App\User');
	}
}
