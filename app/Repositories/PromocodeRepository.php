<?php
namespace App\Repositories;

use App\Promocode;

class PromocodeRepository extends Repository
{
	public function __construct(Promocode $promocode){
		$this->model = $promocode;
		$this->uniqueKey = 'code';
	}
	public function isOwner($user_id,$id){
		return (boolean)$this->model->where('user_id',$user_id)->where('id',$id)->count();
	}
	public function check($user_id, $code){
		return (boolean)$this->model->where('user_id',$user_id)->where('code',$code)->count();
	}
}