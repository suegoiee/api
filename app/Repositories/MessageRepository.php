<?php
namespace App\Repositories;
use App\Message;

class MessageRepository extends Repository
{
	public function __construct(Message $message){
		$this->model = $message;
	}
}