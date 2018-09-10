<?php
namespace App\Repositories;

use App\StockModel;

class StockModelRepository extends Repository
{
	public function __construct(StockModel $stockModel){
		$this->model = $stockModel;
	}
}