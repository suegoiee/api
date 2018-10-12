<?php
namespace App\Repositories;
use App\Analyst;

class AnalystRepository extends Repository
{
	public function __construct(Analyst $model){
		$this->model = $model;
	}
}