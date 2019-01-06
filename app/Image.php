<?php

namespace App;

use App\UanalyzeModel;
class Image extends UanalyzeModel
{
    protected $fillable = [
        'path','sort','link','title','seo'
    ];
    protected $appends=[
    	'url'
    ];
    protected $hidden=[
        'id','type','path', 'imageable_id', 'imageable_type', 'created_at', 'updated_at', 'deleted_at'  
    ];
    public function imageable()
    {
        return $this->morphTo()->orderBy('sort','ASC');
    }
    public function getUrlAttribute(){
    	return url('storage/'.$this->path);
    }
    public function getSeoAttribute($seo)
    {
        return ($seo!=null ? $seo : '');
    }
}
