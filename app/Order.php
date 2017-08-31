<?php

namespace App;

use App\UanalyzeModel;
use Illuminate\Database\Eloquent\SoftDeletes;
class Order extends UanalyzeModel
{
    use SoftDeletes;
    protected $fillable = [
        'user_id','status','price','memo',
    ];
    protected $dates = ['deleted_at'];
    
    public function user(){
    	return $this->belongsTo('App\User');
    }

    public function products(){
        return $this->belongsToMany('App\Product');
    }
}
