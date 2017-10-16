<?php

namespace App;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\UanalyzeModel;
class Socialites extends UanalyzeModel
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $fillable = [
        'email', 'password' ,'name','link','first_name','last_name','gender','locale','timezone','verified',
    ];

    protected $hidden = [
        'password'
    ];
    protected $appends = [];

    public function user(){
        return $this->belongsTo('App\User');
    }
}
