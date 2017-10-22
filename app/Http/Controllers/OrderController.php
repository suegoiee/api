<?php

namespace App\Http\Controllers;

use Allpay;
use Shouwda\Allpay\SDK\PaymentMethod;

use App\Traits\OauthToken;
use App\Repositories\OrderRepository;
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
        $orders = $user->orders()->with(['products'])->get()->makeHidden(['deleted_at']);
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

        $request_data = $request->only(['price', 'memo', 'invoice_name', 'invoice_phone', 'invoice_address', 'company_id', 'invoice_title']);
        $request_data['use_invoice'] =  $request->input('use_invoice',0);
        $request_data['invoice_type'] =  $request->input('invoice_type',0);
        $products = $request->input('products',[]);
        $order = $user->orders()->create($request_data);
        $product_ids = [];
        $product_data = [];
        $order_price = 0;
        foreach($products as $key => $value) {
            $product = $this->productRepository->getWith($value,['collections']);
            $order_price += $product->price;
            if($product->price==0){
                $this->addProducts($user->id, [$product->id]);
            }
            array_push($product_ids,$value);
            array_push($product_data,$product);
        }
        $order->products()->attach($product_ids);
        
        if($order_price>0){
            $allpay_form = $this->allpay_form($order);
            $order['form_html'] = $allpay_form;
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
            foreach ($order_products as $key => $product) {
                if($product->price>0){
                    $this->addProducts($user->id, [$product->id]);
                }
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
            'price' => 'required|numeric',
            'products'=>'exists:products,id,status,1',
            'products.*'=>'exists:products,id,status,1',
        ]);        
    }
    protected function orderPayment(Request $request, $id)
    {
        $user = $request->user();
        if(!($this->orderRepository->isOwner($user->id,$id))){
            return $this->failedResponse(['message'=>[trans('auth.permission_denied')]]);
        }

        $order = $user->orders()->with(['products'])->find($id);
        $allpay_form = $this->allpay_form($order);
        $order['products'] = $order->products;
        $order['form_html'] = $allpay_form;
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
                    'user_id' => $user_id,
                ],
            ]);
        return json_decode((string) $response->getBody(), true);
    }
    private function allpay_form($order){
        $merchant_trade_no = $order->user->id.time();
        $order->allpays()->create(['MerchantTradeNo'=>$merchant_trade_no]);
        $items = [];
        foreach ($order->products as $key => $product) {
            $item = [
                'Name' => $product->name, 
                'Price' => $product->price, 
                'Currency'=> 'NTD', 
                'Quantity' => (int)"1", 
                'URL' => "#"
                ];
            array_push($items, $item);
        }
        $data=[
            'ReturnURL' => env('ALLPAY_RETURN_URL',url('/')).'allpay/feedback',
            'PaymentInfoURL' => env('ALLPAY_PAYMENTINFO_URL',url('/')).'allpay/feedback',
            'ClientBackURL' => env('ALLPAY_BACK_URL'),
            'MerchantTradeNo' => $merchant_trade_no,
            'MerchantTradeDate' => date('Y/m/d H:i:s'),
            'TotalAmount' => $order->price,
            'TradeDesc' => 'Uanalyze',
            'ChoosePayment' => \PaymentMethod::ALL,
            'IgnorePayment' => 'ATM#WebATM#CVS#AccountLink#TopUpUsed',
            'Items' => $items,
        ];
        Allpay::set($data);
        return Allpay::checkOutString();
    }
}
