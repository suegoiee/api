<?php

namespace App;

use App\UanalyzeModel;

class StockIndustry extends UanalyzeModel
{
    protected $connection = 'mysql_2';
	protected $table = 'stock_industries';
	protected $primaryKey = 'id';
    protected $fillable = ['id','stock_code','industry_id'];
    
}
