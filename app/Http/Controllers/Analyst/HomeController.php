<?php

namespace App\Http\Controllers\Analyst;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\OrderRepository;
class HomeController extends Controller
{
    public function index(Request $request,OrderRepository $orderRepository)
    {
    	$user = $request->user();
    	$products = $user->products()->where('status',1)->get();
    	$clients = [];
    	foreach ($products as $key => $product) {
    		foreach ($product->users as $key => $client) {
    			$clients[$client->id]=$client;
    		}
    	}
    	$product_ids = $products->map(function($item,$key){return $item->id;});
    	$orders = $orderRepository->getsWith(['products'],['created_at.>='=>date('Y-08-01'), 'created_at.<'=>date('Y-09-d',strtotime('first day of next month ')),'status'=>1],[],['products'=>function($query) use($product_ids) { $query->whereIn('id',$product_ids); }]);
    	$data=[
    		'product_num'=>$products->count(),
    		'client_num'=>count($clients),
    		'order_num'=>$orders->count(),
    	];
    	return view('analyst.home',$data);
    }
}
