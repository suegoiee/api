<?php

namespace App;

use App\UanalyzeModel;

class Promocode extends UanalyzeModel
{
    protected $fillable = [
        'user_id','name','code','offer','deadline','used_at'
    ];
    protected $appends=['user_name'];
    public function user(){
    	return $this->belongsTo('App\User');
    }
    public function getUserNameAttribute(){
        $user = $this->user;
        return $user ? $user->email: 0;
    }
    public function order()
    {
        return $this->belongsToMany('App\Order');
    }
}
