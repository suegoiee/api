<?php
namespace App\Repositories;

use App\Ecpay;

class EcpayRepository extends Repository
{
	public function __construct(Ecpay $ecpay){
		$this->model = $ecpay;
	}
}