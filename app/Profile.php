<?php

namespace App;

use App\UanalyzeModel;

class Profile extends UanalyzeModel
{
	protected $fillable=['user_id','nike_name','name','sex','address','birthday'];

	public function user(){
		return $this->belongsTo('App\User');
	}
}
