<?php
namespace App\Repositories;
use App\OnlineCourse;

class OnlineCourseRepository extends Repository
{
	public function __construct(OnlineCourse $model){
		$this->model = $model;
	}
}