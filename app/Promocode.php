<?php

namespace App;

use App\UanalyzeModel;

class Promocode extends UanalyzeModel
{
    protected $fillable = [
        'user_id','name','code','offer','deadline','used_at','send','type'
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
    public function used()
    {
        return $this->belongsToMany('App\User', 'promocode_used','promocode_id','user_id');
    }
}
