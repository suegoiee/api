<?php
namespace App\Repositories;

use App\Order;

class OrderRepository extends Repository
{
	public function __construct(Order $model){
		$this->model = $model;
		$this->condition = $model;
	}
	public function isOwner($user_id,$id){
		return (boolean)$this->model->where('user_id',$user_id)->where('id',$id)->count();
	}
}