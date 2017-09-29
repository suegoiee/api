<?php

namespace Shouwda\Allpay;

use Shouwda\Allpay\SDK;
use Illuminate\Support\ServiceProvider;

class Allpay
{
 	protected $opay;

    public function __construct()
    {
        $this->opay = new \AllInOne();
        $this->opay->ServiceURL = config('allpay.ServiceURL');
        $this->opay->HashKey    = config('allpay.HashKey');
        $this->opay->HashIV     = config('allpay.HashIV');
        $this->opay->MerchantID = config('allpay.MerchantID');
        $this->opay->EncryptType = '1';//SHA256
        return $this->opay;
    }
    public function oPayment()
    {
    	return $this->opay;
    }
    public function set($oPayData=[],$value=null)
    {
    	if(is_array($oPayData)){
	    	foreach ($oPayData as $key => $data) {
	    		$this->opay->Send[$key] = $data;
	    	}
    	}else{
    		$this->opay->Send[$oPayData] = $value;
    	}
    }
    public function checkOutString()
    {
    	try {
    		return $this->opay->CheckOutString();
    	} catch (Exception $e) {
    		return  $e->getMessage();
    	} 
    }

    public function checkOutFeedback()
    {
        return $this->opay->CheckOutFeedback();
    }

}