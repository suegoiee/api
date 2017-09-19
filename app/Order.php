<?php

namespace App;

use App\UanalyzeModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends UanalyzeModel
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $appends = ['user_nick_name'];
    protected $fillable = [
        'user_id','status','price','memo',
    ];
    protected $hidden = [
    ];
    public function user(){
    	return $this->belongsTo('App\User');
    }

    public function products(){
        return $this->belongsToMany('App\Product');
    }

    public function getUserNickNameAttribute(){
        return $this->user->profile->nick_name;
    }
}
