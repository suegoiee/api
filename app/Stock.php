<?php

namespace App;

use App\UanalyzeModel;

class Stock extends UanalyzeModel
{
	protected $table = 'company_info';
	//protected $visible = ['stock_code', 'stock_name'];
	protected $primaryKey = 'no';
    protected $fillable = [
        'stock_code','stock_name','stock_industries','industries','info','area','product','local_related_1','local_related_2','local_related_3','local_related_4','local_related_5','foreign_related','type'
    ];
    public function Tags()
    {
        return $this->belongsToMany('App\Tag','stock_tags','stock_no','tag_id');
    }
    public function events()
    {
        return $this->hasMany('App\StockEvents','company_id','no');
    }
    public function products()
    {
        return $this->hasMany('App\StockProduct','company_id','no');
    }
    public function areas()
    {
        return $this->hasMany('App\StockArea','company_id','no');
    }
    public function customers()
    {
        return $this->hasMany('App\StockCustomer','company_id','no');
    }
    public function suppliers()
    {
        return $this->hasMany('App\StockSupplier','company_id','no');
    }
    public function local_relateds()
    {
        return $this->hasMany('App\StockLocalRelated','company_id','no');
    }
    public function foreign_relateds()
    {
        return $this->hasMany('App\StockForeignRelated','company_id','no');
    }
}
