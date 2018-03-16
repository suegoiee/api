<?php

namespace App;

use App\UanalyzeModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class Article extends UanalyzeModel
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    
    protected $fillable = [
        'title','content','top','posted_at','status','slug'
    ];

    public function tags()
    {
        return $this->morphToMany('App\Tag', 'taggable')->withTimestamps();
    }
}
