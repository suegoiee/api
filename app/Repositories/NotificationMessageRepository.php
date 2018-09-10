<?php
namespace App\Repositories;
use App\NotificationMessage;

class NotificationMessageRepository extends Repository
{
	public function __construct(NotificationMessage $model){
		$this->model = $model;
	}
}