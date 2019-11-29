<?php

namespace App;

use App\UanalyzeModel;

class Announcement extends UanalyzeModel
{
    protected $fillable = [
        'title','content','type','status'
    ];
    public function getContentAttribute($value)
    {
        return preg_replace('/\r?\n/i', '<br/>', $value);
    }
}
