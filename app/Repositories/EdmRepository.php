<?php
namespace App\Repositories;
use App\Edm;
class EdmRepository extends Repository
{
	public function __construct(Edm $model){
		$this->model = $model;
	}
}