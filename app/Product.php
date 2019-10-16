<?php

namespace App;

use App\UanalyzeModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends UanalyzeModel
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    
    protected $fillable = [
        'name','model','column','info_short','info_more','type','price','expiration','status','faq','pathname','seo','date_range', 'inflated','category', 'single_type','single_options'
    ];
    protected $appends = [ 'avatar_small', 'avatar_detail'];

    protected $hidden = [ 'pivot' ];
    //protected $visible = ['id', 'name', 'model', 'info_short', 'info_more', 'type', 'price', 'expiration', 'faq'];

    public function users(){
    	return $this->belongsToMany('App\User')->withPivot('deadline','installed')->withTimestamps();
    }
    public function plans(){
        return $this->hasMany('App\Product_price');
    }

    public function faqs(){
        return $this->hasMany('App\Product_faq');
    }

    public function collections(){
    	return $this->belongsToMany('App\Product','product_collections','collection_id','product_id')->select(['id','name','column','model','type','product_collections.sort'])->withTimestamps()->withPivot('sort');
    }
    public function affiliated_products(){
        return $this->belongsToMany('App\Product','affiliated_product','affiliated_product_id','product_id')->select(['id','name','column','model','type','affiliated_product.sort'])->orderBy('affiliated_product.sort','asc')->withTimestamps()->withPivot('sort');
    }

    public function tags()
    {
        return $this->morphToMany('App\Tag', 'taggable')->withTimestamps();
    }

    public function experts()
    {
        return $this->morphToMany('App\Expert', 'expertable')->withTimestamps();
    }
    
    public function solutions() {
        return $this->hasManyThrough('App\Product_solutions', 'App\Product_price', 'product_id', 'product_prices_id');
    }

    public function orders()
    {
        return $this->belongsToMany('App\Order', 'order_product')->withPivot('quantity');
    }

    public function avatars()
    {
        return $this->morphMany('App\Avatar', 'imageable');
    }
    public function getAvatarSmallAttribute()
    {
        return $this->avatars()->where('type','small')->first();
    }

    public function getAvatarDetailAttribute()
    {
        return $this->avatars()->where('type','detail')->get();
    }
    public function getSeoAttribute($seo)
    {
        return ($seo!=null ? $seo : '');
    }
    public function laboratory()//
    {
        return $this->hasOne('App\Laboratory');
    }

    public function laboratories()
    {
        return $this->belongsToMany('App\Laboratory', 'laboratory_product' )->withTimestamps();
    }
}
