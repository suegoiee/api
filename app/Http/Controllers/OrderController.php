<?php

namespace App\Http\Controllers;

use Shouwda\Ecpay\Ecpay;
use Shouwda\Ecpay\SDK\ECPay_PaymentMethod;
use Shouwda\Ecpay\SDK\ECPay_PaymentMethodItem;
use App\Traits\OauthToken;
use App\Repositories\OrderRepository;
use App\Repositories\PromocodeRepository;
use App\Repositories\ProductRepository;
use App\Repositories\EventRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Route;
class OrderController extends Controller
{	
    use OauthToken;
    protected $orderRepository;
    protected $promocodeRepository;
    protected $productRepository;
    protected $eventRepository;
    protected $ecpay;
    public function __construct(OrderRepository $orderRepository, ProductRepository $productRepository, PromocodeRepository $promocodeRepository, EventRepository $eventRepository )
    {
        $this->ecpay = new Ecpay();
        $this->orderRepository = $orderRepository;
        $this->productRepository = $productRepository;
        $this->promocodeRepository = $promocodeRepository;
        $this->eventRepository = $eventRepository;
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
        $products = $request->input('products',[]);
        if(count($products)==0){
            $products = $request->input('product_id', false);
            if($products){
                $quantity = $request->input('product_quantity', false);
                if($quantity === false ){
                    return $this->failedResponse(['message'=>['The quantity is required']]);
                }
                $products = [['id'=>$products, 'quantity'=>$quantity]];
            }else{
                return $this->failedResponse(['message'=>['No product to check order']]);
            }
        }

        $validator = $this->orderValidator($request->all());
        if($validator->fails()){
            return $this->validateErrorResponse($validator->errors()->all());
        }
        $request_data = $request->only(['memo', 'invoice_name', 'invoice_phone', 'invoice_address', 'company_id', 'invoice_title', 'paymentType', 'LoveCode', 'referrer_code']);
        $request_data['paymentType'] = isset($request_data['paymentType']) ? $request_data['paymentType'] : ''; 
        $request_data['use_invoice'] =  $request->input('use_invoice',0);
        $request_data['invoice_type'] =  $request->input('invoice_type',0);
        $request_data['referrer_code'] = isset($request_data['referrer_code']) ? $request_data['referrer_code'] :  '';
        $product_ids = [];
        $product_data = [];
        $product_free = [];
        $order_price = 0;
        foreach($products as $key => $value) {
            $product = $this->productRepository->getWith($value['id'],['collections']);
            if(!$product){
                 return $this->failedResponse(['message'=>['The selected products is invalid.']]);
            }
            $quantity = isset($value['quantity']) ? $value['quantity'] : 1;
            $order_plan = $product->plans()->where('expiration',$quantity)->first();
            if(!$order_plan){
                return $this->failedResponse(['message'=>['product plan is not exists']]);
            }
            $order_price += $order_plan->price;
            if($order_plan->price==0){
                array_push($product_free,['id'=>$product->id, 'quantity'=>$quantity]);
            }
            $product_ids[$value['id']] = ['unit_price'=>$order_plan->price , 'quantity' => $quantity];
            $product_collect = collect($product);
            $product_collect->put('quantity', $quantity);
            array_push($product_data, $product_collect);
        }
        $promocodes = $request->input('promocodes',[]);
        $promocode_ids = [];
        $trail_result = $this->getOrderTrail($user, $products, $promocodes);
        if(isset($trail_result['error'])){
            return $this->failedResponse(['message'=>['product plan is not exists']]);
        }
        foreach ($trail_result['promocodes'] as $key => $value) {
            if(!isset($value['error'])){
                $promocode = $this->promocodeRepository->getBy(['code'=>$key]);
                if($promocode->type==1){
                    $this->promocodeRepository->update($promocode->id, ['used_at'=> date('Y-m-d H:i:s')]);
                }else{
                    $promocode->used()->attach($user->id);
                }
                $promocode_ids[$promocode->id] = ['overflow_offer'=>(isset($value['overflow_offer']) ? $value['overflow_offer'] : 0)];
            }
        }
        $order_price = $trail_result['total_price'];
        if($order_price == 0){
            $product_pass=[];
            foreach ($product_ids as $key => $product) {
                array_push($product_pass, ['id'=>$key, 'quantity'=>$product['quantity']]);
            }
            $bonus_products = $this->checkEvents($product_pass);
            array_push($product_pass, ...$bonus_products);
            $result = $this->addProducts($request, $user->id, $product_pass);
        }else{
            if(count($product_free)>0){
                $result = $this->addProducts($request, $user->id, $product_free);
            }

        }
        $request_data['price'] = $order_price;
        $order = $user->orders()->create($request_data);
        $order->products()->attach($product_ids);
        $order->promocodes()->attach($promocode_ids);
        if($order_price>0){
            $ecpay_form = $this->ecpay_form($order);
            $order['form_html'] = $ecpay_form;
        }

        if($order_price==0){
            $this->orderRepository->update($order->id, ['status'=>1]);
        }
        foreach ($order->products as $key => $product) {
            $product->quantity = $product->pivot->quantity;
        }
        return $this->successResponse($order?$order:[]);
    }
    public function show(Request $request, $id)
    {
        $user = $request->user();
        if(!($this->orderRepository->isOwner($user->id,$id))){
            return $this->failedResponse(['message'=>[trans('auth.permission_denied')]]);
        }

        $order = $user->orders()->with(['products','products.collections','promocodes'=>function($query){
            $query->select(['id','name','offer','deadline']);
        }])->find($id);
        $order->promocodes->makeHidden(['pivot']);

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
        $pre_order = $this->orderRepository->get($id);
        if($pre_order->status==1){
            return $this->successResponse($pre_order?$pre_order:[]);
        }
        $order = $this->orderRepository->update($id,$request_data);

        if(!$order){
            return $this->notFoundResponse();
        }
        $user = $order->user;
        if( $order->status==1){
            $order_products = $order->products;
            $product_data = [];
            $condition_products = [];
            foreach ($order_products as $key => $product) {
                if($product->pivot->unit_price>0){
                    array_push($product_data, ['id'=>$product->id, 'quantity'=>$product->pivot->quantity]);
                }
                array_push($condition_products, ['id'=>$product->id, 'quantity'=>$product->pivot->quantity]);
            }
            $bonus_products = $this->checkEvents($condition_products);
            array_push($product_data, ...$bonus_products);
            if(count($product_data)>0){
                $this->addProducts($request, $user->id, $product_data);
            }
        }else if($order->status==2 || $order->status==4){
            $promocodes = $order->promocodes;
            $cancel_promocode_ids = [];
            foreach ($promocodes as $key => $promocode) {
                if($promocode->type==1){
                    $this->promocodeRepository->update($promocode->id, ['used_at'=>null]);
                }else{
                    $promocode->used()->detach([$user->id]);
                }
                array_push($cancel_promocode_ids, $promocode->id); 
            }
            $order->promocodes()->detach($cancel_promocode_ids);
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
        if($order && ($order->status==0 || $order->status==2 || $order->status==4)){
            $promocodes = $order->promocodes;
            $cancel_promocode_ids = [];
            foreach ($promocodes as $key => $promocode) {
                if($promocode->type==1){
                    $this->promocodeRepository->update($promocode->id, ['used_at'=>null]);
                }else{
                    $promocode->used()->detach([$user->id]);
                }
                
                array_push($cancel_promocode_ids, $promocode->id); 
            }
            $order->promocodes()->detach($cancel_promocode_ids);
            $this->orderRepository->delete($id);  
            return $this->successResponse(['message'=>['The order was deleted'], 'deleted'=>1]);
        }
        return $this->failedResponse(['message'=>[trans('order.delete_error')]]);
    }
    public function cancel(Request $request, $id)
    {   
        $order = $this->orderRepository->get($id);
        if(!$order){
            return $this->notFoundResponse();
        }
        if($order->status!=1){
            return $this->failedResponse(['message'=>[trans('order.cancel_error')]]);
        }
        $user = $order->user;
        if( $order->status==1){
            $order_products = $order->products;
            $product_data = [];
            foreach ($order_products as $key => $product) {
                if($product->pivot->unit_price>0){
                    array_push($product_data, ['id'=>$product->id, 'quantity'=>$product->pivot->quantity]);
                }
            }
            if(count($product_data)>0){
                $this->removeProducts($request, $user->id, $product_data);
                $order = $this->orderRepository->update($id, ['status'=>5]);
            }
        }

        return $this->successResponse($order?$order:[]);
    }
    protected function orderValidator(array $data)
    {
        return Validator::make($data, [
            //'user_id' => 'required|exists:users,id',
            //'price' => 'required|numeric',
            //'products.id'=>'exists:products,id,status,1',
            //'products.*.id'=>'exists:products,id,status,1',
            'paymentType'=>'in:credit,atm,webatm,cvs,barcode',
            'use_invoice'=>'in:0,1,2',
            'invoice_type'=>'in:0,1,2',
        ]);        
    }
    protected function orderPayment(Request $request, $id)
    {
        $user = $request->user();
        if(!($this->orderRepository->isOwner($user->id,$id))){
            return $this->failedResponse(['message'=>[trans('auth.permission_denied')]]);
        }

        $order = $user->orders()->with(['products','promocodes'])->find($id);
        $ecpay_form = $this->ecpay_form($order);
        $order['products'] = $order->products;
        $order['promocodes'] = $order->promocodes;
        $order['form_html'] = $ecpay_form;
        return $this->successResponse($order?$order:[]);
    }
    private function addProducts($request, $user_id, $products){
        $token = $this->clientCredentialsGrantToken($request);
        $request->request->add([
            'products' => $products,
            'user_id' => $user_id,
        ]);
        $tokenRequest = $request->create(
            url('/user/products'),
            'post'
        );
        $tokenRequest->headers->set('Accept','application/json');
        $tokenRequest->headers->set('Authorization','Bearer '.$token['access_token']);
        $instance = Route::dispatch($tokenRequest);

        return json_decode($instance->getContent(), true);
    }
    private function removeProducts($request, $user_id, $products){
        $token = $this->clientCredentialsGrantToken($request);
        $request->request->add([
            'products' => $products,
            'user_id' => $user_id,
        ]);
        $tokenRequest = $request->create(
            url('/user/products/cancel'),
            'put'
        );
        $tokenRequest->headers->set('Accept','application/json');
        $tokenRequest->headers->set('Authorization','Bearer '.$token['access_token']);
        $instance = Route::dispatch($tokenRequest);

        return json_decode($instance->getContent(), true);
    }
    private function ecpay_form($order){
        $merchant_trade_no = $order->user->id.time();
        $order->ecpays()->create(['MerchantTradeNo'=>$merchant_trade_no]);
        $relate_number = str_pad($order->user->id, 6, '0', STR_PAD_LEFT).'i'.(microtime(true)*10000); 
        $order->update(['RelateNumber'=>$relate_number]);
        $items = [];
        $invoiceItems = [];
        $item_name = '';
        $item_count = '';
        $item_price = '';
        $item_word = '';
        foreach ($order->products as $key => $product) {
            $item = [
                'Name' => $product->name, 
                'Price' => $product->pivot->unit_price, 
                'Currency'=> 'NTD', 
                'Quantity' => 1, 
                'URL' => "#"
                ];
            $invoiceItem=[
                'Name'=>$product->name,
                'Count'=>1,
                'Word'=>'期',
                'Price'=>$product->pivot->unit_price,
                'TaxType'=>1,
            ];
            array_push($items, $item);
            array_push($invoiceItems, $invoiceItem);
        }
        foreach ($order->promocodes as $key => $promocode) {
            $item = [
                'Name' => $promocode->name, 
                'Price' => -($promocode->offer - $promocode->pivot->overflow_offer), 
                'Currency'=> 'NTD', 
                'Quantity' => 1, 
                'URL' => "#"
                ];
            array_push($items, $item);
            $invoiceItem=[
                'Name'=>$promocode->name,
                'Count'=>1,
                'Word'=>'期',
                'Price'=> -($promocode->offer - $promocode->pivot->overflow_offer),
                'TaxType'=>1,
            ];
            array_push($invoiceItems, $invoiceItem);
        }
        $data=[
            'ReturnURL' => env('ECPAY_RETURN_URL',url('/')).'/ecpay/feedback',
            'PaymentInfoURL' => env('ECPAY_PAYMENTINFO_URL',url('/')).'/ecpay/feedback',
            'ClientBackURL' => env('ECPAY_BACK_URL',url('/')),
            'OrderResultURL' => env('ECPAY_ORDER_RESULT_URL',url('/')).'/ecpay/result',
            'MerchantTradeNo' => $merchant_trade_no,
            'MerchantTradeDate' => date('Y/m/d H:i:s'),
            'TotalAmount' => $order->price,
            'TradeDesc' => 'Uanalyze',
            'ChoosePayment' => \ECPay_PaymentMethod::Credit,
            'ChooseSubPayment' => \ECPay_PaymentMethodItem::None,
            'Items' => $items,
            'InvoiceMark'=>$order->use_invoice==2 ? 'Y':'',
        ];
        $extendData = [];
        if($order->use_invoice==2){
            $extendData['CustomerID'] = 'm'.str_pad($order->user->id, 6, '0', STR_PAD_LEFT);
            $extendData['CustomerName'] = '';//urlencode($order->invoice_name);
            $extendData['CustomerAddr'] = '';//$order->invoice_address);
            $extendData['CustomerPhone'] = $order->invoice_phone;
            $extendData['CustomerEmail'] = $order->user->email;
            if($order->company_id && strlen($order->company_id) <= 8){
                $extendData['CustomerIdentifier'] = $order->company_id;
            }
            $extendData['TaxType'] = 1;
            $extendData['Donation'] = $order->invoice_type==0 ? 1:'';
            $extendData['LoveCode']= $order->invoice_type==0 ? $order->LoveCode:'';
            $extendData['Print'] = 0;
            $extendData['InvoiceItems'] = $invoiceItems;
            $extendData['RelateNumber'] = $order->RelateNumber;
            $extendData['InvType'] = '07';
        }
        switch ($order->paymentType) {
            case 'credit':
                $data['ChoosePayment'] = \ECPay_PaymentMethod::Credit;
                break;
            case 'atm':
                $data['ChoosePayment'] = \ECPay_PaymentMethod::ATM;
                $data['ClientBackURL'] = env('ECPAY_BACK_URL',url('/')).'?order_status=3';
                //$extendData['PaymentInfoURL'] = '';
                //$extendData['expireDate'] = '';
                break;
            case 'barcode':
                $data['ChoosePayment'] = \ECPay_PaymentMethod::BARCODE;
                $data['ClientBackURL'] = env('ECPAY_BACK_URL',url('/')).'?order_status=3';
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
                $data['ClientBackURL'] = env('ECPAY_BACK_URL',url('/')).'?order_status=3';
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
        $this->ecpay->set($data, null, $extendData);
        return $this->ecpay->checkOutString();
    }
    public function trial(Request $request)
    {
        $user = $request->user();
        $products = $request->input('products',[]);
        if(count($products)>0){
            foreach ($products as $key => $product_id) {
                $product = $this->productRepository->get($product_id['id']);
                if(!$product){
                     return $this->failedResponse(['message'=>['The selected products is invalid.']]);
                }
            }  
        }else{
            $products = $request->input('product_id', false);
            if($products){
                $quantity = $request->input('product_quantity', false);
                if($quantity === false ){
                    return $this->failedResponse(['message'=>['The quantity is required']]);
                }
                $products = [['id'=>$products, 'quantity'=>$quantity]];
                foreach ($products as $key => $product_id) {
                    $product = $this->productRepository->get($product_id['id']);
                    if(!$product){
                        return $this->failedResponse(['message'=>['The selected products is invalid.']]);
                    }
                }
            }else{
                return $this->failedResponse(['message'=>['The product is required']]);
            }
        }
        $promocode_codes = $request->input('promocodes',[]);
        $check_order = $this->delOrderByUsePromocode($user, $promocode_codes);
        $result = $this->getOrderTrail($user, $products, $promocode_codes);
        if(!$result){
            return $this->failedResponse(['message'=>['plans error']]);
        }
        $result['check_order'] = $check_order;
        return $this->successResponse($result);
    }
    function delOrderByUsePromocode($user, $promocode_codes){
        $order_check = [];
        $check_order = 0;
        foreach ($promocode_codes as $key => $promocode_code) {
            $orders = $user->orders()->with('promocodes')->whereIn('status', [0, 2, 4])->whereHas('promocodes', function($query)use($promocode_code){
                $query->where('code',$promocode_code);
            })->get();
            foreach ($orders as $key => $order) {
                $promocodes = $order->promocodes;
                $cancel_promocode_ids = [];
                foreach ($promocodes as $key => $promocode) {
                    if($promocode->type==1){
                        $this->promocodeRepository->update($promocode->id, ['used_at'=>null]);
                    }else{
                        $promocode->used()->detach([$user->id]);
                    }
                    
                    array_push($cancel_promocode_ids, $promocode->id); 
                }
                $order->promocodes()->detach($cancel_promocode_ids);
                $order->delete();
            }
            if($orders->count()!=0){
                $order_check[$promocode_code]=1;
                $check_order=1;
            }else{
                $order_check[$promocode_code]=0;
            }
        }
        return $check_order;
    }
    function getOrderTrail($user, $products, $promocode_codes)
    {
        $products_data = collect($products);
        $product_ids = $products_data->map(function($item, $key){return $item['id'];})->toArray();
        $product_offers = [];
        $product_promocodes = [];
        $order_promocodes = [];
        $order_offer = 0;
        $result = ['origin_price'=>0, 'total_price'=>0, 'promocodes'=>[]];
        foreach ($promocode_codes as $key => $promocode_code) {
            if(!$this->promocodeRepository->check($user->id, $promocode_code)){
                $result['promocodes'][$promocode_code]=[ 'msg' => 'not exists','error'=>1];
            }else{
                $promocode = $this->promocodeRepository->getBy(['user_id'=>$user->id,'code'=>$promocode_code]);

                if(!$promocode){
                    $promocode = $this->promocodeRepository->getBy(['user_id'=>0, 'type'=>0, 'code'=>$promocode_code]);
                }
                $user_promocode_used = $promocode->used()->where('user_id',$user->id)->count();
                $promocode_use_time = $promocode->used()->count();
                if($promocode->disabled == 1){
                    $result['promocodes'][$promocode_code]=[ 'msg' => 'Used','error'=>6];
                }else if($promocode->used_at != null || $user_promocode_used != 0 ||
                            ( $promocode->times_limit != 0 && $promocode_use_time >= $promocode->times_limit )){
                    $result['promocodes'][$promocode_code]=[ 'msg' => 'Used','error'=>2];
                }else if($promocode->deadline !=null && strtotime($promocode->deadline. ' +1 day') <= time()){
                    $result['promocodes'][$promocode_code]=[ 'msg' => 'Expired','error'=>3];
                }else if( $promocode->specific==1 && $promocode->products()->whereIn('id',$product_ids)->count()==0){
                    $result['promocodes'][$promocode_code]=[ 'msg' => 'Not match','error'=>4];
                }else if( $promocode->specific==1 && $promocode->retrict_type == 1 && $products_data->where('quantity',$promocode->retrict_condition)->count()==0){
                    $result['promocodes'][$promocode_code]=[ 'msg' => 'Retrict condition not match','error'=>6];
                }else if(array_key_exists($promocode_code,$result['promocodes'])){
                    continue;
                }else{
                    if($promocode->specific==1){
                        array_push($product_offers, $promocode);
                    }else{
                        $result['promocodes'][$promocode->code] = ['name'=>$promocode->name, 'offer'=>$promocode->offer, 'overflow_offer'=>0];
                        $order_offer += $promocode->offer;
                    }
                }
            }
        }
        foreach ($products as $key => $value) {
            array_push($product_ids, $value['id']);
            $product = $this->productRepository->getWith($value['id'],['collections']);
            if($product){
                $quantity = isset($value['quantity']) ? $value['quantity'] : 1;

                $product_plan = $product->plans()->where('expiration',$quantity)->first();
                if(!$product_plan){
                    return false;
                }
                $product_price = $product_plan->price;
                $result['total_price'] += $product_price;
                $result['origin_price'] += $product_price;
                $min_diff = $product_price;
                $use_promocode = false;
                foreach ($product_offers as $key => $product_promocode) {
                    if($product_promocode->products()->where('id', $product->id)->count()!=0){
                        if( $min_diff > abs($product_price - $product_promocode->offer)){
                            $min_diff = abs($product_price - $product_promocode->offer);
                            $use_promocode = $product_promocode;
                        } 
                    }
                }
                if($use_promocode){
                    $order_promocodes[$product->id] = $use_promocode;
                    $order_promocodes[$product->id]->overflow_offer = $use_promocode->offer > $product_price ? $use_promocode->offer - $product_price:0;
                }
            }
        }
        foreach ($order_promocodes as $key => $product_promocode) {
            $result['promocodes'][$product_promocode->code] = ['name'=>$product_promocode->name, 'offer'=>$product_promocode->offer, 'overflow_offer'=>$product_promocode->overflow_offer];
            $result['total_price'] = $result['total_price'] <= ($product_promocode->offer-$product_promocode->overflow_offer) ? 0 : $result['total_price'] - ($product_promocode->offer-$product_promocode->overflow_offer);
        }

        foreach ($product_offers as $key => $product_promocode) {
            if(!array_key_exists($product_promocode->code, $result['promocodes'])){
                $result['promocodes'][$product_promocode->code] = [ 'msg' => 'This promocode of the product is in use','error'=>5];
            
            }
        }
        $result['total_price'] = $result['total_price'] <= $order_offer ? 0 : $result['total_price'] - $order_offer;
        return $result;
    }
    function checkEvents($products)
    {
        $products = collect($products);
        $evnets = $this->eventRepository->getsWith(['condition_products','products'],['status'=>1]);
        $bonus_products = [];
        foreach ($evnets as $key => $evnet) {
            if($evnet->type == 1){
                $pass = true;
                foreach ($evnet->condition_products as $key => $condition_product) {
                    $product = $products->where('id', $condition_product->id)->first();
                    if(!$product || $product->quantity < $condition_product->pivot->quantity){
                        $pass = false ;
                    }
                }

                if($pass){
                    foreach ($evnet->products as $key => $product) {
                        array_push($bonus_products, ['id'=>$product->id, 'quantity'=>$product->pivot->quantity]]);
                    }
                }
            }
        }
        return $bonus_products;
    }
}
