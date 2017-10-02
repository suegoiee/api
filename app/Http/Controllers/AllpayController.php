<?php

namespace App\Http\Controllers;

use Allpay;

use App\Traits\OauthToken;
use App\Repositories\AllpayRepository;
use Shouwda\Allpay\SDK\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AllpayController extends Controller
{	
    use OauthToken;
    protected $allpayRepository;

    public function __construct(AllpayRepository $allpayRepository)
    {
        $this->allpayRepository = $allpayRepository;
    }

    public function index()
    {
        //test code
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
        echo 'Allpay post test';
        echo Allpay::checkOutString();
        return '';
    }
    public function feedback(Request $request){
        try {
            $feedback_data = Allpay::checkOutFeedback();
            //$feedback_data = $request->all();
            $allpay = $this->allpayRepository->getBy(['MerchantTradeNo'=>$feedback_data['MerchantTradeNo']]);
            if($allpay){
                $allpay->feedbacks()->create($feedback_data);
                if($feedback_data['RtnCode']==1){
                    $this->order_update($allpay->order_id,1);
                }else{
                    $this->order_update($allpay->order_id,2);
                }
                return '1|OK';
            }else{
                return '0|Allpay record not found';
            }
            
        } catch (Exception $e) {
            return '0|'.$e->getMessage();
        } 
    }
    private function order_update($order_id,$status){
        $token = $this->clientCredentialsGrantToken();
        $http = new \GuzzleHttp\Client;
        $response = $http->request('put',url('/user/orders/'.$order_id),[
                'headers'=>[
                    'Accept' => 'application/json',
                    'Authorization' => 'Bearer '.$token['access_token'],
                ],
                'form_params' => [
                    'status' => $status
                ],
            ]);
        return json_decode((string) $response->getBody(), true);
    }
}
