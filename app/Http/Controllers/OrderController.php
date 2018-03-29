<?php

namespace App\Http\Controllers;

use Ecpay;
use Shouwda\Ecpay\SDK\ECPay_PaymentMethod;

use App\Traits\OauthToken;
use App\Repositories\OrderRepository;
use App\Repositories\PromocodeRepository;
use App\Repositories\ProductRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{	
    use OauthToken;
    protected $orderRepository;
    protected $productRepository;
    public function __construct(OrderRepository $orderRepository,ProductRepository $productRepository)
    {
	   $this->orderRepository = $orderRepository;
       $this->productRepository = $productRepository;
    }

    public function index(Request $request)
    {
        $user = $request->user();
        $orders = $user->orders()->with(['products'])->orderBy('created_at','DESC')->get()->makeHidden(['deleted_at']);
        return $this->successResponse($orders);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $user = $request->user();
        $validator = $this->orderValidator($request->all());
        if($validator->fails()){
            return $this->validateErrorResponse($validator->errors()->all());
        }

        $request_data = $request->only(['price', 'memo', 'invoice_name', 'invoice_phone', 'invoice_address', 'company_id', 'invoice_title', 'paymentType']);
        $request_data['use_invoice'] =  $request->input('use_invoice',0);
        $request_data['invoice_type'] =  $request->input('invoice_type',0);
        $products = $request->input('products',[]);
        $product_ids = [];
        $product_data = [];
        $product_free = [];
        $order_price = 0;
        foreach($products as $key => $value) {
            $product = $this->productRepository->getWith($value['id'],['collections']);
            $order_price += $product->price * (int)$value['quantity'];
            if($product->price==0){
                array_push($product_free,['id'=>$product->id, 'quantity'=>1]);
                //$result = $this->addProducts($user->id, [['id'=>$product->id, 'quantity'=>1]]);
            }
            //array_push($product_ids, $value['id']);
            $product_ids[$value['id']] = ['unit_price'=>$product->price , 'quantity' => $value['quantity']];
            array_push($product_data, $product);
        }
        if(count($product_free)>0){
            $result = $this->addProducts($user->id, $product_free);
        }
        $request_data['price'] = $order_price;
        $order = $user->orders()->create($request_data);
        $order->products()->attach($product_ids);
        
        if($order_price>0){
            $ecpay_form = $this->ecpay_form($order);
            $order['form_html'] = $ecpay_form;
        }

        if($order_price==0){
            $this->orderRepository->update($order->id, ['status'=>1]);
        }

        $order['products'] = $product_data;
        return $this->successResponse($order?$order:[]);
    }

    public function show(Request $request, $id)
    {
        $user = $request->user();
        if(!($this->orderRepository->isOwner($user->id,$id))){
            return $this->failedResponse(['message'=>[trans('auth.permission_denied')]]);
        }

        $order = $user->orders()->with(['products','products.collections'])->find($id);

        return $this->successResponse($order?$order:[]);
    }

    public function edit($id)
    {
      
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->only('status'),['status' => 'required|numeric']);
        if($validator->fails()){
            return $this->validateErrorResponse($validator->errors()->all());
        }

        $request_data = $request->only('status');

        $order = $this->orderRepository->update($id,$request_data);

        if(!$order){
            return $this->notFoundResponse();
        }
        $user = $order->user;
        if($order->status==1){
            $order_products = $order->products;
            $product_data = [];
            foreach ($order_products as $key => $product) {
                if($product->pivot->unit_price>0){
                    array_push($product_data, ['id'=>$product->id, 'quantity'=>$product->pivot->quantity]);
                    //$this->addProducts($user->id,  [['id'=>$product->id, 'quantity'=>$product->pivot->quantity]]);
                }
            }
            if(count($product_data)>0){
                $this->addProducts($user->id, $product_data);
            }
        }

        return $this->successResponse($order?$order:[]);
    }

    public function destroy(Request $request, $id)
    {
        $user = $request->user();
        if(!($this->orderRepository->isOwner($user->id,$id))){
            return $this->failedResponse(['message'=>[trans('auth.permission_denied')]]);
        }
        $order = $this->orderRepository->get($id);
        if($order && $order->status==0){
            $this->orderRepository->delete($id);  
            return $this->successResponse(['id'=>$id]);
        }
        return $this->failedResponse(['message'=>[trans('order.delete_error')]]);
    }

    protected function orderValidator(array $data)
    {
        return Validator::make($data, [
            //'user_id' => 'required|exists:users,id',
            //'price' => 'required|numeric',
            'products.id'=>'exists:products,id,status,1',
            'products.*.id'=>'exists:products,id,status,1',
        ]);        
    }
    protected function orderPayment(Request $request, $id)
    {
        $user = $request->user();
        if(!($this->orderRepository->isOwner($user->id,$id))){
            return $this->failedResponse(['message'=>[trans('auth.permission_denied')]]);
        }

        $order = $user->orders()->with(['products'])->find($id);
        $ecpay_form = $this->ecpay_form($order);
        $order['products'] = $order->products;
        $order['form_html'] = $ecpay_form;
        return $this->successResponse($order?$order:[]);
    }
    private function addProducts($user_id, $products){
        $token = $this->clientCredentialsGrantToken();
        $http = new \GuzzleHttp\Client;
        $response = $http->request('post',url('/user/products'),[
                'headers'=>[
                    'Accept' => 'application/json',
                    'Authorization' => 'Bearer '.$token['access_token'],
                ],
                'form_params' => [
                    'products' => $products,
                    'user_id' => $user_id
                ],
            ]);
        return json_decode((string) $response->getBody(), true);
    }
    private function ecpay_form($order){
        $merchant_trade_no = $order->user->id.time();
        $order->ecpays()->create(['MerchantTradeNo'=>$merchant_trade_no]);
        $items = [];
        foreach ($order->products as $key => $product) {
            $item = [
                'Name' => $product->name, 
                'Price' => $product->pivot->unit_price, 
                'Currency'=> 'NTD', 
                'Quantity' => (int)$product->pivot->quantity, 
                'URL' => "#"
                ];
            array_push($items, $item);
        }
        switch ($order->paymentType) {
            case 'credit':
                $paymentMethod = \ECPay_PaymentMethod::Credit;
                break;
            case 'atm':
                $paymentMethod = \ECPay_PaymentMethod::ATM;
                break;
            case 'atm':
                $paymentMethod = \ECPay_PaymentMethod::BARCODE;
                break;
            default:
                $paymentMethod = \ECPay_PaymentMethod::Credit;
                break;
        }
        $data=[
            'ReturnURL' => env('ECPAY_RETURN_URL',url('/')).'/ecpay/feedback',
            'PaymentInfoURL' => env('ECPAY_PAYMENTINFO_URL',url('/')).'/ecpay/feedback',
            'ClientBackURL' => env('ECPAY_BACK_URL',url('/')),
            'MerchantTradeNo' => $merchant_trade_no,
            'MerchantTradeDate' => date('Y/m/d H:i:s'),
            'TotalAmount' => $order->price,
            'TradeDesc' => 'Uanalyze',
            'ChoosePayment' => \ECPay_PaymentMethod::Credit,
            'ChooseSubPayment' => \ECPay_PaymentMethodItem::None,
            'Items' => $items,
        ];
        $extendData = [];
        switch ($order->paymentType) {
            case 'credit':
                $data['ChoosePayment'] = \ECPay_PaymentMethod::Credit;
                break;
            case 'atm':
                $data['ChoosePayment'] = \ECPay_PaymentMethod::ATM;
                //$extendData['PaymentInfoURL'] = '';
                //$extendData['expireDate'] = '';
                break;
            case 'barcode':
                $data['ChoosePayment'] = \ECPay_PaymentMethod::BARCODE;
                //$extendData['Desc_1'] = '';
                //$extendData['Desc_2'] = '';
                //$extendData['Desc_3'] = '';
                //$extendData['Desc_4'] = '';
                //$extendData['PaymentInfoURL'] = '';
                //$extendData['ClientRedirectURL'] = '';
                //$extendData['StoreExpireDate'] = '';
                break;
            case 'cvs':
                $data['ChoosePayment'] = \ECPay_PaymentMethod::CVS;
                //$extendData['Desc_1'] = '';
                //$extendData['Desc_2'] = '';
                //$extendData['Desc_3'] = '';
                //$extendData['Desc_4'] = '';
                //$extendData['PaymentInfoURL'] = '';
                //$extendData['ClientRedirectURL'] = '';
                //$extendData['StoreExpireDate'] = '';
                break;
            case 'webatm':
                $data['ChoosePayment'] = \ECPay_PaymentMethod::WebATM;
                break;
            default:
                break;
        }
        Ecpay::set($data, null, $extendData);
        return Ecpay::checkOutString();
    }
    public function priceTrial(Request $request,PromocodeRepository $promocodeRepository)
    {
        $user = $request->user();
        $products = $request->input('products',[]);
        $promocodes = $request->input('promocodes',[]);
        $result = ['total_price'=>0, 'promocodes'=>[]];
        foreach ($products as $key => $value) {
            $product = $this->productRepository->getWith($value['id'],['collections']);
            $result['total_price'] += $product->price * (int)$value['quantity'];
        }
        foreach ($promocodes as $key => $value) {
            if(!$promocodeRepository->check($user->id, $value)){
                $result['promocodes'][$value]=['msg'=>'error'];
            }else{
                $promocode = $promocodeRepository->getBy(['user_id'=>$user->id,'code'=>$value]);
                $result['promocodes'][$value]=['name'=>$promocode->name, 'offer'=>$promocode->offer];
                $result['total_price'] = $result['total_price'] <= $promocode->offer ? 0 : $result['total_price'] - $promocode->offer;
            }
        }
        return $this->successResponse($result);
    }
}
