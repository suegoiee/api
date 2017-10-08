<?php

namespace App;

use App\UanalyzeModel;

class Tag extends UanalyzeModel
{
    protected $fillable = [
        'name'
    ];

    public function Products()
    {
        return $this->morphedByMany('App\Product', 'taggable');
    }
    public function Stocks()
    {
        return $this->belongsToMany('App\Tag','stock_tags','tag_id','stock_id');
    }
}
