<?php
namespace App\Repositories;
use App\PhysicalCourse;

class PhysicalCourseRepository extends Repository
{
	public function __construct(PhysicalCourse $model){
		$this->model = $model;
	}
}