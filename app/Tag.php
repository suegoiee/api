<?php

namespace App;

use App\UanalyzeModel;

class Tag extends UanalyzeModel
{
    protected $fillable = [
        'name'
    ];
    protected $hidden = ['pivot','created_at','updated_at'];

    public function products()
    {
        return $this->morphedByMany('App\Product', 'taggable');
    }
    public function stocks()
    {
        return $this->belongsToMany('App\Stock','stock_tags','tag_id','stock_no');
    }
}
