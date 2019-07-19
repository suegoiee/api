<?php

namespace App;

use App\UanalyzeModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends UanalyzeModel
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $appends = ['user_nickname', 'no','user_email'];
    protected $fillable = [
        'user_id','status','price','memo', 'use_invoice', 'invoice_type', 'invoice_name', 'invoice_phone', 'invoice_address', 'company_id', 'invoice_title','paymentType', 'LoveCode', 'RelateNumber', 'referrer_code'
    ];
    protected $hidden = [
        'user_id'
    ];
    public function user(){
    	return $this->belongsTo('App\User');
    }

    public function allpays(){
        return $this->hasMany('App\Allpay');
    }
    public function ecpays(){
        return $this->hasMany('App\Ecpay');
    }
    public function capitals(){
        return $this->hasMany('App\Capital');
    }

    public function products(){
        return $this->belongsToMany('App\Product')->select(['id','name','type','order_product.quantity as quantity','order_product.unit_price as price'])->withPivot('unit_price','quantity');
    }

    public function getUserNicknameAttribute(){
        $user = $this->user;
        $profile = $user ? $this->user->profile : false;

        $nickname = $profile ? $profile->nickname : '-';

        return $nickname;
    }
    public function getUserEmailAttribute(){
        $user = $this->user;
        return $user ? $user->email:'-';
    }

    public function getNoAttribute(){
        $no =  '103'.str_pad($this->id, 5, '0', STR_PAD_LEFT);
        return $no;
    }
    public function promocodes()
    {
        return $this->belongsToMany('App\Promocode')->withPivot('overflow_offer');
    }
}
