<?php

namespace App;

use App\UanalyzeModel;

class Product extends UanalyzeModel
{
    protected $fillable = [
        'name','model','info_short','info_more','type','price'
    ];

    public function user(){
    	return $this->belongsToMany('App\User')->withPivot('title', 'deadline')->withTimestamps();
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

}
