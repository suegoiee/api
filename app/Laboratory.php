<?php

namespace App;

use App\UanalyzeModel;
use Illuminate\Database\Eloquent\SoftDeletes;
class Laboratory extends UanalyzeModel
{
	use SoftDeletes;
	protected $fillable=['user_id','title','layout'];

	public function avatar()
    {
        return $this->morphMany('App\Avatar', 'imageable');
    }

	public function products(){
		return $this->belongsToMany('App\Product')->withTimestamps();
	}
}
