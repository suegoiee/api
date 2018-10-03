<?php
namespace App\Http\Controllers\Analyst;
use App\Repositories\OrderRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
class OrderController extends AnalystController
{	
    public function __construct(Request $request, OrderRepository $orderRepository)
    {
        parent::__construct($request);
        $this->moduleName='order';
        $this->moduleRepository = $orderRepository;

        //$this->token = $this->clientCredentialsGrantToken();
    }

    public function index(Request $request)
    {
        $month = date('Y-m').'-01';
        $where= [
            'status' => 1,
            'price.<>'=>0
        ];
        $query_string=[];
        if($request->has('start_date')){
            $where['created_at.>=']=$request->input('start_date');
            $query_string['start_date'] = $request->input('start_date');
        }
        if($request->has('end_date')){
            $where['created_at.<=']=$request->input('end_date');
            $query_string['end_date'] = $request->input('end_date');
        }
        $user = $request->user();
        $products_ids = $user->products->map(function($item, $key){return $item->id;})->toArray();

        $orders = $this->moduleRepository->getsWith(['products','promocodes'], $where, ['created_at'=>'DESC'], ['products'=>function($query) use ($products_ids){
                $query->whereIn('id',$products_ids);
            }]);
        $order_products = [];
        foreach ($orders as $key => $order) {
            foreach ($order->products as $key2 => $product) {
                if(in_array($product->id,$products_ids)){
                    $order_product = clone $order;
                    $order_product->product_name = $product->name;
                    $order_product->product_id = $product->id;
                    $offer = 0;
                    $overflow_offer = 0;
                    foreach ($order->promocodes as $key3 => $promocode){
                        if($promocode->products()->where('id',$product->id)->count())
                        {
                            $offer += $promocode->offer;
                            $overflow_offer += $promocode->pivot->overflow_offer;
                        }
                    }
                    $order_product->product_price = $product->pivot->unit_price * $product->pivot->quantity;
                    $order_product->order_price = $order_product->product_price<$offer ? 0:$order_product->product_price - $offer + $overflow_offer;
                    $order_product->handle_fee = round($this->getHandleFee($order->paymentType, $order_product->order_price, $order->created_at), 2);
                    $order_product->platform_fee = (($order_product->order_price/1.05) - $order_product->handle_fee)*0.3;
                    $order_product->platform_fee = $order_product->platform_fee < 0 ? 0 : $order_product->platform_fee;
                    array_push($order_products, $order_product);
                }
            }
        }

        $data = [
            'module_name'=> $this->moduleName,
            'subtitle'=> 'detail',
            'actions'=>[],
            'tools'=>['date_range'],
            'tabs'=>[],
            'query_string' => $query_string,
            'table_data' => $order_products,
            'table_head' =>['product_name', 'product_id', 'product_price','no','user_nickname','order_price','status','created_at','paymentType','handle_fee','platform_fee'],
            'table_formatter' =>['status','paymentType','platform_fee'],
            'table_action'=>false,
        ];
        return view('analyst.list',$data);
    }

    public function create()
    {
        $data = [
            'module_name'=> $this->moduleName,
            'data'=>null,
        ];
        return view('admin.form',$data);
    }

    public function edit($id)
    {

        $data = [
            'module_name'=> $this->moduleName,
            'data' => $this->moduleRepository->getWith($id,['products']),
        ];
        return view('admin.form',$data);
    }
    private function getHandleFee($paymentType, $price, $date){
        switch ($paymentType) {
            case '':return 0;
            case 'credit':return ($price*0.0275<5) ? 5:( $price * 0.0275);
            case 'webatm':case 'atm':($price*0.0275<5) ? 5:($price*0.0275>26)?26:($price*0.0275);
            case 'cvs':return strtotime($date) < strtotime('2018-09-01')? 26:30;
            default:return 0;
        }

    }
}
