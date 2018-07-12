<?php
namespace Shouwda\Ecpay;
include('SDK/Ecpay_Invoice.php');

use Shouwda\Ecpay\SDK;
use Illuminate\Support\ServiceProvider;

class Ecpay
{
 	protected $ecpay;
    protected $ecpayInvoice;

    public function __construct()
    {
        $this->ecpay = new \ECPay_AllInOne();
        $this->ecpay->ServiceURL = config('ecpay.ServiceURL');
        $this->ecpay->HashKey    = config('ecpay.HashKey');
        $this->ecpay->HashIV     = config('ecpay.HashIV');
        $this->ecpay->MerchantID = config('ecpay.MerchantID');
        $this->ecpay->EncryptType = '1';//SHA256

        $this->ecpayInvoice = new \EcpayInvoice();
        $this->ecpayInvoice->MerchantID      = config('ecpay.MerchantID');
        $this->ecpayInvoice->HashKey         = config('ecpay.InvoiceHashKey');
        $this->ecpayInvoice->HashIV          = config('ecpay.InvoiceHashIV');
        
    }
    public function ecPayment()
    {
    	return $this->ecpay;
    }
    public function ecInvoice()
    {
        return $this->ecpayInvoice;
    }
    public function set($ecPayData=[], $value=null, $ecPayExtendData=[], $extendValue=null)
    {
    	if(is_array($ecPayData)){
	    	foreach ($ecPayData as $key => $data) {
	    		$this->ecpay->Send[$key] = $data;
	    	}
    	}else{
    		$this->ecpay->Send[$ecPayData] = $value;
    	}

        if(is_array($ecPayExtendData)){
            foreach ($ecPayExtendData as $key => $data) {
                $this->ecpay->SendExtend[$key] = $data;
            }
        }else{
            $this->ecpay->SendExtend[$ecPayExtendData] = $extendValue;
        }
    }
    public function invoiceMethod($method, $url){
        $this->ecpayInvoice->Invoice_Method = $method;
        $this->ecpayInvoice->Invoice_Url = config('ecpay.InvoiceURL').'/'.$url;
    }
    public function setInvoice($ecPayData=[], $value=null, $ecPayExtendData=[], $extendValue=null)
    {
        if(is_array($ecPayData)){
            foreach ($ecPayData as $key => $data) {
                $this->ecpayInvoice->Send[$key] = $data;
            }
        }else{
            $this->ecpayInvoice->Send[$ecPayData] = $value;
        }

        if(is_array($ecPayExtendData)){
            foreach ($ecPayExtendData as $key => $data) {
                $this->ecpayInvoice->SendExtend[$key] = $data;
            }
        }else{
            $this->ecpayInvoice->SendExtend[$ecPayExtendData] = $extendValue;
        }
    }
    public function invoiceCheckOut(){
        return $this->ecpayInvoice->Check_Out();
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