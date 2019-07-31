<?php

namespace App;

use App\UanalyzeModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class Laboratory extends UanalyzeModel
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];

	protected $fillable=['user_id','title','category','layout','customized', 'sort', 'product_id' , 'pathname'];

    protected $hidden=['user_id','created_at', 'updated_at', 'deleted_at'];

	protected $appends = [ 'avatar' , 'master'];
	
	public function avatars()
    {
        return $this->morphMany('App\Avatar', 'imageable');
    }

    public function getAvatarAttribute()
    {
        return $this->avatars()->orderBy('created_at','DESC')->first();
    }

	public function products(){
        $user_id = $this->user_id;
		return $this->belongsToMany('App\Product')->with(['users'])->orderBy('pivot_sort', 'ASC')->withPivot('sort')->withTimestamps();
	}
    public function product(){
        return $this->belongsTo('App\Product');
    }
    public function getMasterAttribute()
    {
        $collection = $this->product()->with('faqs')->first();
        if(!$collection){
            return null;
        }
        return $collection->makeHidden(['status', 'users', 'info_short', 'info_more', 'price', 'expiration', 'created_at', 'updated_at', 'deleted_at', 'avatar_small', 'avatar_detail','sort','date_range','inflated','faqs','faq']);
    }
}
