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
}
