<?php
namespace App\Repositories;

use App\Plan;

class PlanRepository extends Repository
{
	public function __construct(Plan $plan){
		$this->model = $plan;
	}
}