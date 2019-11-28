<?php

namespace App;
use App\UanalyzeModel;
class Socialite extends UanalyzeModel
{
    protected $fillable = [
        'email' ,'name','provider_id', 'provider','access_token'
    ];

    protected $appends = [];

    public function user(){
        return $this->belongsTo('App\User');
    }
}
