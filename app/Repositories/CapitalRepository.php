<?php
namespace App\Repositories;

use App\Capital;

class CapitalRepository extends Repository
{
	public function __construct(Capital $model){
		$this->model = $model;
	}
}