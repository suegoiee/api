<?php

namespace App;

use App\UanalyzeModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class Favorite extends UanalyzeModel
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'name','code'
    ];

    protected $hidden = [
        'user_id',
    ];
    public function user(){
    	return $this->belongsTo('App\User');
    }

}
