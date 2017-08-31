<?php
namespace App\Repositories;
use App\Avatar;
class AvatarRepository extends Repository
{
	public function __construct(Avatar $avatar){
		$this->model = $avatar;
	}
	public function isOwner($user_id,$id){
		return (boolean)$this->model->where('user_id',$user_id)->where('id',$id)->count();
	}
}