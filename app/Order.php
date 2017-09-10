<?php

namespace App;

use App\UanalyzeModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends UanalyzeModel
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];

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
}
