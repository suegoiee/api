<?php

namespace App;

use App\UanalyzeModel;

class Avatar extends UanalyzeModel
{

    protected $fillable = [
        'path','type'
    ];

    public function imageable()
    {
        return $this->morphTo();
    }
}
