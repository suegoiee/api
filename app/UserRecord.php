<?php

namespace App;

use App\UanalyzeModel;
class UserRecord extends UanalyzeModel
{
	protected $table = 'user_records';
	public $timestamps = false;
    protected $fillable = [
        'stock_code', 'ip', 'user_id','created_at'
    ];
    public function user(){
    	return $this->belongsTo('App\User');
    }
}
