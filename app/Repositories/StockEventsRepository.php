<?php
namespace App\Repositories;
use App\StockEvents;

class StockEventsRepository extends Repository
{
	public function __construct(StockEvents $stockEvents){
		$this->model = $stockEvents;
	}
}