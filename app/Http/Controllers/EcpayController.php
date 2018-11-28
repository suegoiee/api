<?php

namespace App\Http\Controllers;

use Ecpay;

use App\Traits\OauthToken;
use App\Repositories\EcpayRepository;
//use Shouwda\Ecpay\SDK\ECPay_PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Route;

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
            $feedback_data['data']= json_encode($feedback_data);
            //$feedback_data = $request->all();
            $ecpay = $this->ecpayRepository->getBy(['MerchantTradeNo'=>$feedback_data['MerchantTradeNo']]);
            if($ecpay){
                $ecpay->feedbacks()->create($feedback_data);
                $order = $ecpay->order;
                switch ($order->paymentType) {
                    case 'credit':case 'webatm':
                        if($feedback_data['RtnCode']==1){
                            $this->order_update($request, $ecpay->order_id,1);
                        }else{
                            $this->order_update($request, $ecpay->order_id,2);
                        }
                        return '1|OK';
                    case 'atm':
                        if($feedback_data['RtnCode']==1){
                            $this->order_update($request, $ecpay->order_id,1);
                        }else if($feedback_data['RtnCode']==2){
                            $this->order_update($request, $ecpay->order_id,3);
                        }else{
                            $this->order_update($request, $ecpay->order_id,4);
                        }
                        return '1|OK';
                    case 'barcode':case 'cvs':
                        if($feedback_data['RtnCode']==1){
                            $this->order_update($request, $ecpay->order_id,1);
                        }else if($feedback_data['RtnCode']==10100073){
                            $this->order_update($request, $ecpay->order_id,3);
                        }else{
                            $this->order_update($request, $ecpay->order_id,4);
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
    public function testorder(Request $request){
        return $this->order_update($request,4765,1);
    }
    private function order_update($request, $order_id, $status){
        $token = $this->clientCredentialsGrantToken($request);
        $request->request->add([
            'status' => $status
        ]);
        $tokenRequest = $request->create(
            url('/user/orders/'.$order_id),
            'put'
        );
        $tokenRequest->headers->set('Accept','application/json');
        $tokenRequest->headers->set('Authorization','Bearer '.$token['access_token']);
        $instance = Route::dispatch($tokenRequest);

        return json_decode($instance->getContent(), true);
    }
    public function result(Request $request)
    {
        $feedback_data = Ecpay::checkOutFeedback();

        $feedback_data['data']= json_encode($feedback_data);
        $ecpay = $this->ecpayRepository->getBy(['MerchantTradeNo'=>$feedback_data['MerchantTradeNo']]);
        if($ecpay){
            $ecpay->feedbacks()->create($feedback_data);
            $order = $ecpay->order;
            switch ($order->paymentType) {
                case 'credit':case 'webatm':
                        if($feedback_data['RtnCode']==1){
                            $this->order_update($request, $ecpay->order_id,1);
                            return redirect(env('ECPAY_BACK_URL',url('/')).'?order_status=1');
                        }
                        $this->order_update($request, $ecpay->order_id,2);
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
    public function invoiceQuery(Request $request, $order_id){
        $user = $request->user();
        $order = $user->orders()->find($order_id);
        if($order){
            $relateNumber = $order->RelateNumber;
            if(!$relateNumber){
                return $this->successResponse([]);
            }
        }else{
            return $this->successResponse([]);
        }
        Ecpay::invoiceMethod('INVOICE_SEARCH', 'Query/Issue');
        Ecpay::setInvoice('RelateNumber', $relateNumber);
        try{
            $origin_invoice = Ecpay::invoiceCheckOut();
            if($origin_invoice['RtnCode']=='1'){
                $invoice = [
                    'create_date' => isset($origin_invoice['IIS_Create_Date']) ? $origin_invoice['IIS_Create_Date'] : '',
                    'category' => isset($origin_invoice['IIS_Category']) ? $origin_invoice['IIS_Category'] : '',
                    'identifier' => isset($origin_invoice['IIS_Identifier']) ? $origin_invoice['IIS_Identifier'] : '',
                    'number' => isset($origin_invoice['IIS_Number']) ? $origin_invoice['IIS_Number'] : '',
                    'random_number' => isset($origin_invoice['IIS_Random_Number']) ? $origin_invoice['IIS_Random_Number'] : '',
                    'sales_amount' => isset($origin_invoice['IIS_Sales_Amount']) ? $origin_invoice['IIS_Sales_Amount'] : '',
                    'check_number' => isset($origin_invoice['IIS_Check_Number']) ? $origin_invoice['IIS_Check_Number'] : '',
                    'upload_date' => isset($origin_invoice['IIS_Upload_Date']) ? $origin_invoice['IIS_Upload_Date'] : '',
                    'upload_status' => isset($origin_invoice['IIS_Upload_Status']) ? $origin_invoice['IIS_Upload_Status'] : '',
                    'invoice_remark' => isset($origin_invoice['InvoiceRemark']) ? $origin_invoice['InvoiceRemark'] : '',
                    'pos_barcode' => isset($origin_invoice['PosBarCode']) ? $origin_invoice['PosBarCode'] : '',
                    'qrcode_left' => isset($origin_invoice['QRCode_Left']) ? $origin_invoice['QRCode_Left'] : '',
                    'qrcode_right' => isset($origin_invoice['QRCode_Right']) ? $origin_invoice['QRCode_Right'] : '',
                    'print_flag' => isset($origin_invoice['IIS_Print_Flag']) ? $origin_invoice['IIS_Print_Flag'] : '',
                    'turnkey_status' => isset($origin_invoice['IIS_Turnkey_Status']) ? $origin_invoice['IIS_Turnkey_Status'] : '',
                    'issue_status' => isset($origin_invoice['IIS_Issue_Status']) ? $origin_invoice['IIS_Issue_Status'] : '',
                    'invalid_status' => isset($origin_invoice['IIS_Invalid_Status']) ? $origin_invoice['IIS_Invalid_Status'] : '',
                    'remain_allowance_amt' => isset($origin_invoice['IIS_Remain_Allowance_Amt']) ? $origin_invoice['IIS_Remain_Allowance_Amt'] : '',
                    'award_flag' => isset($origin_invoice['IIS_Award_Flag']) ? $origin_invoice['IIS_Award_Flag'] : '',
                    'award_type' => isset($origin_invoice['IIS_Award_Type']) ? $origin_invoice['IIS_Award_Type'] : '',
                ];
                return $this->successResponse($invoice);
            }else{
                return $this->failedResponse([$origin_invoice['RtnMsg']]);
            }
        }
        catch (Exception $e)
        {
            return $this->successResponse([]);
        }
    }
}
