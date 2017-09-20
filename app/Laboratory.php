<?php

namespace App;

use App\UanalyzeModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class Laboratory extends UanalyzeModel
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];

	protected $fillable=['user_id','title','layout'];

	protected $appends = [ 'avatar' ];
	
	public function avatars()
    {
        return $this->morphMany('App\Avatar', 'imageable');
    }

    public function getAvatarAttribute()
    {
        return $this->avatars()->first();
    }

	public function products(){
		return $this->belongsToMany('App\Product')->withTimestamps();
	}
}
