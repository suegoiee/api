<?php
namespace App\Repositories;
use App\Portfolio;
class PortfolioRepository extends Repository
{
	public function __construct(Portfolio $model){
		$this->model = $model;
		$this->condition = $model;
	}
}