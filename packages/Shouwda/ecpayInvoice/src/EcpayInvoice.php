<?php
namespace Shouwda\EcpayInvoice;
use Illuminate\Support\ServiceProvider;
include('SDK/Ecpay_Invoice.php');
class EcpayInvoice
{
    protected $ecpayInvoice;

    public function __construct()
    {
        $this->ecpayInvoice = new \EcpayInvoice();
        $this->ecpayInvoice->MerchantID      = config('ecpay.MerchantID');
        $this->ecpayInvoice->HashKey         = config('ecpay.InvoiceHashKey');
        $this->ecpayInvoice->HashIV          = config('ecpay.InvoiceHashIV');
        
    }
    public function ecInvoice()
    {
        return $this->ecpayInvoice;
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
}