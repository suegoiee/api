<?php

namespace App\Http\Controllers;

use Ecpay;

use App\Traits\OauthToken;
use App\Repositories\EcpayRepository;
use Shouwda\Ecpay\SDK\ECPay_PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EcpayController extends Controller
{	
    use OauthToken;
    protected $ecpayRepository;

    public function __construct(EcpayRepository $ecpayRepository)
    {
        $this->ecpayRepository = $ecpayRepository;
    }

    public function index()
    {
        //test code
        $data=[
            'ReturnURL' => url('/ecpay/test'),
            'MerchantTradeNo' => 'Test'.time(),
            'MerchantTradeDate' => date('Y/m/d H:i:s'),
            'TotalAmount' => '2000',
            'TradeDesc' => 'Test DESC',
            'ChoosePayment' => \ECPay_PaymentMethod::Credit,
            'Items' => [
                    ['Name' => 'Test product', 'Price' => (int)"2000", 'Currency'=>'NTD', 'Quantity' => (int) "1", 'URL' => "" ],
                ],
        ];
        Ecpay::set($data);
        echo 'Ecpay post test';
        echo Ecpay::checkOutString();
        return '';
    }
    public function feedback(Request $request){
        try {
            $feedback_data = Ecpay::checkOutFeedback();
            //$feedback_data = $request->all();
            $ecpay = $this->ecpayRepository->getBy(['MerchantTradeNo'=>$feedback_data['MerchantTradeNo']]);
            if($ecpay){
                $ecpay->feedbacks()->create($feedback_data);
                $order = $ecpay->order;
                switch ($order->paymentType) {
                    case 'credit':case 'webatm':
                        if($feedback_data['RtnCode']==1){
                            $this->order_update($ecpay->order_id,1);
                        }else{
                            $this->order_update($ecpay->order_id,2);
                        }
                        return '1|OK';
                    case 'atm':
                        if($feedback_data['RtnCode']==1){
                            $this->order_update($ecpay->order_id,1);
                        }else if($feedback_data['RtnCode']==2){
                            $this->order_update($ecpay->order_id,3);
                        }else{
                            $this->order_update($ecpay->order_id,4);
                        }
                        return '1|OK';
                    case 'barcode':case 'cvs':
                        if($feedback_data['RtnCode']==1){
                            $this->order_update($ecpay->order_id,1);
                        }else if($feedback_data['RtnCode']==10100073){
                            $this->order_update($ecpay->order_id,3);
                        }else{
                            $this->order_update($ecpay->order_id,4);
                        }
                        return '1|OK';
                    default:
                        return '0|Payment type error';
                }
            }else{
                return '0|Ecpay record not found';
            }
        } catch (Exception $e) {
            return '0|'.$e->getMessage();
        }
    }
    private function order_update($order_id, $status){
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
    public function result(Request $request)
    {
        $feedback_data = $request->all();
        $ecpay = $this->ecpayRepository->getBy(['MerchantTradeNo'=>$feedback_data['MerchantTradeNo']]);
        if($ecpay){
            $order = $ecpay->order;
            switch ($order->paymentType) {
                case 'credit':case 'webatm':
                        if($feedback_data['RtnCode']==1){
                            return redirect(env('ECPAY_BACK_URL',url('/')).'?order_status=1');
                        }
                        return redirect(env('ECPAY_BACK_URL',url('/')).'?order_status=2');
                case 'atm':
                        if($feedback_data['RtnCode']==1){
                            return redirect(env('ECPAY_BACK_URL',url('/')).'?order_status=1');
                        }else if($feedback_data['RtnCode']==2){
                            return redirect(env('ECPAY_BACK_URL',url('/')).'?order_status=3');
                        }
                        return redirect(env('ECPAY_BACK_URL',url('/')).'?order_status=4');
                case 'barcode':case 'cvs':
                        if($feedback_data['RtnCode']==1){
                            return redirect(env('ECPAY_BACK_URL',url('/')).'?order_status=1');
                        }else if($feedback_data['RtnCode']==10100073){
                            return redirect(env('ECPAY_BACK_URL',url('/')).'?order_status=3');
                        }
                        return redirect(env('ECPAY_BACK_URL',url('/')).'?order_status=4');
                default:
                        return redirect(env('ECPAY_BACK_URL',url('/')).'?order_status=2');
            }
        }else{
            return redirect(env('ECPAY_BACK_URL',url('/')).'?order_status=2');
        }
    }
}
