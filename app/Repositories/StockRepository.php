<?php
namespace App\Repositories;
use App\Stock;

class StockRepository extends Repository
{
	public function __construct(Stock $stock){
		$this->model = $stock;
	}
	public function getIndustreis(){
		return $this->model->select('stock_industries')->distinct()->orderBy('stock_industries')->get();
	}
}