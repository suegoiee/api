<?php

namespace App;

use App\UanalyzeModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class CreditCard extends UanalyzeModel
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];

	protected $table='credit_cards';
    protected $fillable = [
        'number','month','year','check'
    ];
    protected $hidden = [
        'user_id',
    ];
    public function user(){
    	return $this->belongsTo('App\User');
    }
}
