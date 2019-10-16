<?php
namespace App\Repositories;
use App\OrderCourse;

class OrderCourseRepository extends Repository
{
	public function __construct(OrderCourse $OrderCourse){
		$this->model = $OrderCourse;
	}
}