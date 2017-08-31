<?php
namespace App\Repositories;
use App\Profile;
class ProfileRepository extends Repository
{
	public function __construct(Profile $profile){
		$this->model = $profile;
	}
	public function isOwner($user_id,$id){
		return (boolean)$this->model->where('user_id',$user_id)->where('id',$id)->count();
	}
}