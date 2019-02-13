<?php

namespace App;

use App\UanalyzeModel;

class Promocode extends UanalyzeModel
{
    protected $fillable = [
        'user_id','name','code','offer','deadline','used_at','send','type','retrict_type','retrict_condition','times_limit','disabled'
    ];
    protected $appends=['user_name'];
    public function user(){
    	return $this->belongsTo('App\User');
    }
    public function getUserNameAttribute(){
        $user = $this->user;
        return $user ? $user->email: '';
    }
    public function order()
    {
        return $this->belongsToMany('App\Order');
    }
    public function used()
    {
        return $this->belongsToMany('App\User', 'promocode_used','promocode_id','user_id');
    }
    public function products()
    {
        return $this->belongsToMany('App\Product');
    }
}
