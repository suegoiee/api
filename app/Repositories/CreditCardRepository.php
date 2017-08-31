<?php
namespace App\Repositories;
use App\CreditCard;

class CreditCardRepository extends Repository
{
	public function __construct(CreditCard $creditCard){
		$this->model = $creditCard;
	}
	public function isOwner($user_id,$id){
		return (boolean)$this->model->where('user_id',$user_id)->where('id',$id)->count();
	}
}