<?php

namespace App;

use App\UanalyzeModel;

class Stock extends UanalyzeModel
{
	protected $table = 'company_info';
	protected $visible = ['Stock_ID', 'name'];
	protected $primaryKey='no';
    public function Tags()
    {
        return $this->belongsToMany('App\Tag','stock_tags','stock_no','tag_id');
    }
}
