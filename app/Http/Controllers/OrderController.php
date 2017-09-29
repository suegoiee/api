<?php

namespace App\Http\Controllers;

use App\Traits\OauthToken;
use App\Repositories\OrderRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{	
    use OauthToken;
    protected $orderRepository;

    public function __construct(OrderRepository $orderRepository)
    {
	   $this->orderRepository = $orderRepository;
    }

    public function index(Request $request)
    {
        $user = $request->user();
        return $this->successResponse($user->orders);
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

        $request_data = $request->only(['price','memo']);
        $products = $request->input('products',[]);
        $order = $user->orders()->create($request_data);
        $order->products()->attach($products);
        $order['products']=$order->products;
        return $this->successResponse($order?$order:[]);
    }

    public function show(Request $request, $id)
    {
        $user = $request->user();
        if(!($this->orderRepository->isOwner($user->id,$id))){
            return $this->failedResponse(['message'=>[trans('auth.permission_denied')]]);
        }

        $order = $user->orders()->with(['products','products.avatar_small','products.collections'])->find($id);

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

        if($order->status==1){
            return $this->orderPaid($request,$order);
        }

        return $this->successResponse($order?$order:[]);
    }

    public function destroy(Request $request, $id)
    {
        $user = $request->user();
        if(!($this->orderRepository->isOwner($user->id,$id))){
            return $this->failedResponse(['message'=>[trans('auth.permission_denied')]]);
        }
        $this->orderRepository->delete($id);
        return $this->successResponse(['id'=>$id]);

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
    private function orderPaid(Request $request,$order){
        $products = $order->products->map(function($item,$key){return $item->id;});
        
        $token = $this->clientCredentialsGrantToken();
        $http = new \GuzzleHttp\Client;
        $response = $http->request('post',url('/user/products'),[
                'headers'=>[
                    'Accept' => 'application/json',
                    'Authorization' => 'Bearer '.$token['access_token'],
                ],
                'form_params' => [
                    'products' => $products->toArray(),
                    'user_id' => $order->user->id,
                ],
            ]);
        return json_decode((string) $response->getBody(), true);
    }
}
