<?php
namespace App\Repositories;
use App\Referrer;

class ReferrerRepository extends Repository
{
	public function __construct(Referrer $model){
		$this->model = $model;
	}
}