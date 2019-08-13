<?php
namespace App\Repositories;
use App\Expert;

class ExpertRepository extends Repository
{
	public function __construct(Expert $expert){
		$this->model = $expert;
	}
}