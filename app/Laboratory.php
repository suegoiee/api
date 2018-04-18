<?php

namespace App;

use App\UanalyzeModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class Laboratory extends UanalyzeModel
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];

	protected $fillable=['user_id','title','layout','customized', 'sort', 'collection_product_id'];

    protected $hidden=['user_id','created_at', 'updated_at', 'deleted_at'];

	protected $appends = [ 'avatar', 'master' ];
	
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
    public function collection_products(){
        $user_id = $this->user_id;
        return $this->belongsTo('App\Product','collection_product_id','id')->with(['users']);
    }
    public function getMasterAttribute()
    {
        return $this->collection_products()->first();
    }
}
