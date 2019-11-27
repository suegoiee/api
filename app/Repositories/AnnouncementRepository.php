<?php
namespace App\Repositories;
use App\Announcement;

class AnnouncementRepository extends Repository
{
	public function __construct(Announcement $model){
		$this->model = $model;
		$this->condition = $model;
	}
}