<?php
namespace App\Repositories;

use App\Order;

class OrderRepository extends Repository
{
	public function __construct(Order $order){
		$this->model = $order;
	}
	public function isOwner($user_id,$id){
		return (boolean)$this->model->where('user_id',$user_id)->where('id',$id)->count();
	}
}