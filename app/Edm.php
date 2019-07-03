<?php

namespace App;

use App\UanalyzeModel;

class Edm extends UanalyzeModel
{

	protected $fillable=['name', 'sort', 'status'];
	
	public function images()
    {
        return $this->morphMany('App\Image', 'imageable')->orderBy('sort','asc');
    } 
}
