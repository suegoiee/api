<?php

namespace App;

use App\UanalyzeModel;

class Profile extends UanalyzeModel
{
	protected $fillable=['user_id','nick_name','name','sex','address','birthday'];
	protected $hidden = [
        'id','user_id',
    ];
	public function user(){
		return $this->belongsTo('App\User');
	}
}
