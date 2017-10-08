<?php
namespace App\Repositories;
use App\Stock;

class StockRepository extends Repository
{
	public function __construct(Stock $stock){
		$this->model = $stock;
	}
}