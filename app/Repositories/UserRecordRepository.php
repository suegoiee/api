<?php
namespace App\Repositories;

use App\UserRecord;

class UserRecordRepository extends Repository
{
	public function __construct(UserRecord $model){
		$this->model = $model;
	}
}