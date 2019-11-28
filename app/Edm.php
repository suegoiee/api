<?php

namespace App;

use App\UanalyzeModel;

class Edm extends UanalyzeModel
{

    const TABLE = 'edms';
    public $timestamps = false;

    /**
     * {@inheritdoc}
     */
    protected $table = self::TABLE;

	protected $fillable=['name', 'sort', 'status'];
	
	public function images()
    {
        return $this->morphMany('App\Image', 'imageable')->orderBy('sort','asc');
    } 
}
