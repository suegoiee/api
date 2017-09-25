<?php

namespace App;

use App\UanalyzeModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class Message extends UanalyzeModel
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'name','email','category','content'
    ];
}
