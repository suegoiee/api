<?php

namespace App;

use App\UanalyzeModel;

class Stock extends UanalyzeModel
{
	protected $table = 'company_info';
	//protected $visible = ['stock_code', 'stock_name'];
	protected $primaryKey = 'no';
    protected $fillable = [
        'stock_code','stock_name','stock_industries','info','area','product','local_related_1','local_related_2','local_related_3','local_related_4','local_related_5','foreign_related','type'
    ];
    public function Tags()
    {
        return $this->belongsToMany('App\Tag','stock_tags','stock_no','tag_id');
    }
    public function events()
    {
        return $this->hasMany('App\StockEvents','company_id','no');
    }
}
