<?php

namespace Shouwda\Ecpay;

use Shouwda\Ecpay\SDK;
use Illuminate\Support\ServiceProvider;

class Ecpay
{
 	protected $ecpay;

    public function __construct()
    {
        $this->ecpay = new \ECPay_AllInOne();
        $this->ecpay->ServiceURL = config('ecpay.ServiceURL');
        $this->ecpay->HashKey    = config('ecpay.HashKey');
        $this->ecpay->HashIV     = config('ecpay.HashIV');
        $this->ecpay->MerchantID = config('ecpay.MerchantID');
        $this->ecpay->EncryptType = '1';//SHA256
        return $this->ecpay;
    }
    public function ecPayment()
    {
    	return $this->ecpay;
    }
    public function set($ecPayData=[],$value=null)
    {
    	if(is_array($ecPayData)){
	    	foreach ($ecPayData as $key => $data) {
	    		$this->ecpay->Send[$key] = $data;
	    	}
    	}else{
    		$this->ecpay->Send[$ecPayData] = $value;
    	}
    }
    public function checkOutString()
    {
    	try {
    		return $this->ecpay->CheckOutString();
    	} catch (Exception $e) {
    		return  $e->getMessage();
    	} 
    }

    public function checkOutFeedback()
    {
        return $this->ecpay->CheckOutFeedback();
    }

}