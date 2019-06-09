<?php
namespace App\Repositories;

use App\Event;

class EventRepository extends Repository
{
	public function __construct(Event $model){
		$this->model = $model;
		$this->condition = $model;
	}
}