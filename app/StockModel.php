<?php

namespace App;

use App\UanalyzeModel;

class StockModel extends UanalyzeModel
{
    protected $connection = 'mysql_2';
	protected $table = 'rankings';
	protected $primaryKey = 'id';
    protected $fillable = ['name','data','model'];
    
}
