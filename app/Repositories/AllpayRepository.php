<?php
namespace App\Repositories;

use App\Allpay;

class AllpayRepository extends Repository
{
	public function __construct(Allpay $allpay){
		$this->model = $allpay;
	}
}