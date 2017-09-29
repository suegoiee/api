<?php

namespace App\Http\Controllers;

use Allpay;
use Shouwda\Allpay\SDK\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AllpayController extends Controller
{	
    public function __construct()
    {
	  
    }

    public function index()
    {
        $data=[
            'ReturnURL' => url('/allapy/test'),
            'MerchantTradeNo' => 'Test'.time(),
            'MerchantTradeDate' => date('Y/m/d H:i:s'),
            'TotalAmount' => '2000',
            'TradeDesc' => 'Test DESC',
            'ChoosePayment' => \PaymentMethod::Credit,
            'Items' => [
                    ['Name' => 'Test product', 'Price' => (int)"2000", 'Currency'=>'NTD', 'Quantity' => (int) "1", 'URL' => "" ],
                ],
        ];
        Allpay::set($data);
        echo Allpay::checkOutString();
    }
    public function ok(){
        return 'ok';
    }
}
