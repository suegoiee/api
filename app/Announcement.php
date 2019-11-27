<?php

namespace App;

use App\UanalyzeModel;

class Announcement extends UanalyzeModel
{
    protected $fillable = [
        'title','content','type','status'
    ];
}
