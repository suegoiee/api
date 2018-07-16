<?php
namespace App\Repositories;

use App\StockIndustry;

class StockIndustryRepository extends Repository
{
	public function __construct(StockIndustry $stockIndustry){
		$this->model = $stockIndustry;
	}
}