<?php
namespace App\Repositories;
use App\Laboratory;
class LaboratoryRepository extends Repository
{
	public function __construct(Laboratory $model){
		$this->model = $model;
		$this->condition = $model;
	}
	public function isOwner($user_id,$id){
		return (boolean)$this->model->where('user_id',$user_id)->where('id',$id)->count();
	}
}