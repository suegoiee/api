<?php

namespace App;

use App\UanalyzeModel;

class Stock extends UanalyzeModel
{
	protected $table = 'company_info';
	protected $visible = ['Stock_ID', 'name'];
    public function Tags()
    {
        return $this->belongsToMany('App\Tag','stock_tags','stock_id','tag_id');
    }
}
