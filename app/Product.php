<?php

namespace App;

use App\UanalyzeModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends UanalyzeModel
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    
    protected $fillable = [
        'name','model','info_short','info_more','type','price','expiration','status'
    ];

    public function user(){
    	return $this->belongsToMany('App\User')->withPivot('deadline','installed')->withTimestamps();
    }

    public function collections(){
    	return $this->belongsToMany('App\Product','product_collections','collection_id','product_id')->withTimestamps();
    }

    public function tags()
    {
        return $this->morphToMany('App\Tag', 'taggable')->withTimestamps();
    }

    public function avatar()
    {
        return $this->morphMany('App\Avatar', 'imageable');
    }
    public function avatar_small()
    {
        return $this->avatar()->where('type','small');
    }

    public function avatar_detail()
    {
        return $this->avatar()->where('type','detail');
    }

    public function laboratories()
    {
        return $this->belongsToMany('App\Laboratory', 'laboratory_product' )->withTimestamps();
    }
}
