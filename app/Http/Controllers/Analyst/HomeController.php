<?php

namespace App\Http\Controllers\Analyst;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\OrderRepository;
use App\Repositories\PromocodeRepository;
class HomeController extends Controller
{
    public function index(Request $request, OrderRepository $orderRepository, PromocodeRepository $promocodeRepository)
    {
    	$user = $request->user();
    	$products = $user->products()->where('status',1)->get();
    	$clients = [];
    	$product_ids = $products->map(function($item,$key){return $item->id;});
    	$orders = $orderRepository->getsWith(['products'],['created_at.>='=>date('Y-m-01'), 'created_at.<'=>date('Y-m-d',strtotime('first day of next month ')),'status'=>1],[],['products'=>function($query) use($product_ids) { $query->whereIn('id',$product_ids); }]);
        $sales_amounts = 0;
        foreach ($orders as $key => $order) {
            $order_products = $order->products()->whereIn('id',$product_ids)->get();
            $client = $order->user;
            $clients[$client->id]=$client;
            foreach ( $order_products as $key2 => $product) {
                $offer = 0;
                $overflow_offer = 0;
                    foreach ($order->promocodes as $key3 => $promocode){
                        if($promocode->products()->where('id',$product->id)->count())
                        {
                            $offer += $promocode->offer;
                            $overflow_offer += $promocode->pivot->overflow_offer;
                        }
                    }
                    $product_price = $product->pivot->unit_price;// * $product->pivot->quantity;
                    $sales_amounts += $product_price<$offer ? 0:$product_price - $offer + $overflow_offer;
            }
        }
        $pre_orders = $orderRepository->getsWith(['products'],['created_at.<'=>date('Y-m-01'), 'status'=>1],[],['products'=>function($query) use($product_ids) { $query->whereIn('id',$product_ids); }]);
        foreach ($pre_orders as $key => $pre_order) {
            $client = $order->user;
            $clients[$client->id]=$client;
        }
        $promocodes = $promocodeRepository->getsWith(['used','products'],[],[],['products'=>function($query) use($product_ids) { $query->whereIn('id',$product_ids); }]);
        $promocode_num = 0;
        foreach ($promocodes as $key => $value) {
            if($value->used->count()>0){
                $promocode_num +=1 ;
            }
        }
    	$data=[
    		'product_num'=>$products->count(),
    		'client_num'=>count($clients),
    		'order_num'=>$orders->count(),
            'promocode_num'=>$promocode_num,
            'sales_amounts'=>$sales_amounts,
    	];
    	return view('analyst.home',$data);
    }
}
