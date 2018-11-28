<?php

namespace App\Http\Controllers\Admin;
use App\Repositories\ProductRepository;
use App\Repositories\AnalystRepository;
use App\Repositories\OrderRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
class AnalystController extends AdminController
{	
    protected $productRepository;
    protected $orderRepository;
    public function __construct(Request $request, AnalystRepository $analystRepository, ProductRepository $productRepository,OrderRepository $orderRepository)
    {
        parent::__construct($request);
        $this->moduleName='analyst';
        $this->moduleRepository = $analystRepository;
        $this->productRepository = $productRepository;
        $this->orderRepository = $orderRepository;
        //$this->token = $this->clientCredentialsGrantToken();
    }

    public function index()
    {
        $analysts = $this->moduleRepository->getsWith();
        $data = [
            'actionName'=>__FUNCTION__,
            'module_name'=> $this->moduleName,
            'actions'=>['new'],
            'tools'=>['options'=>false],
            'table_data' => $analysts,
            'table_head' =>['id','no','email','name','updated_at'],
            'table_formatter' =>[],
        ];
        return view('admin.list',$data);
    }

    public function grantList($id)
    {
        $analyst = $this->moduleRepository->get($id);
        $grants = $analyst->grants;
        $data = [
            'actionName'=>__FUNCTION__,
            'analyst'=>$analyst,
            'module_name'=> $this->moduleName,
            'actions'=>['new'],

            'table_data' => $grants,
            'table_head' =>['id','statement_no','year_month'],
            'table_formatter' =>[],
        ];
        return view('admin.analyst.grant_list',$data);
    }

    public function create()
    {
        $data = [
            'actionName'=>__FUNCTION__,
            'module_name'=> $this->moduleName,
            'data'=>null,
            'products'=>$this->productRepository->getsWith([],[],['status'=>'DESC']),
        ];
        return view('admin.form',$data);
    }

    public function grantCreate($id)
    {   
        $analyst = $this->moduleRepository->get($id);
        $data = [
            'actionName'=>__FUNCTION__,
            'analyst'=>$analyst,
            'module_name'=> $this->moduleName,
            'data'=>null,
            'details'=>[]
        ];
        return view('admin.analyst.grant_form',$data);
    }

    public function edit($id)
    {
        $analyst = $this->moduleRepository->get($id);
        $data = [
            'actionName'=>__FUNCTION__,
            'module_name'=> $this->moduleName,
            'data' => $analyst,
            'products'=>$this->productRepository->getsWith([],[],['status'=>'DESC']),
        ];
        return view('admin.form',$data);
    }
    public function grantEdit($analyst_id, $id)
    {
        $analyst = $this->moduleRepository->get($analyst_id);
        $grant = $analyst->grants()->find($id);
        $data = [
            'actionName'=>__FUNCTION__,
            'module_name'=> $this->moduleName,
            'analyst'=>$analyst,
            'data' => $grant,
            'details'=>$grant->details
        ];
        return view('admin.analyst.grant_form',$data);
    }
    public function show($id)
    {
        $analyst = $this->moduleRepository->get($id);
        $data = [
            'actionName'=>__FUNCTION__,
            'module_name'=> $this->moduleName,
            'title_field'=> $analyst->profile->nick_name,
            'data' => $analyst,
        ];
        return view('admin.detail',$data);
    }
    public function store(Request $request)
    {
        $validator = $this->analystCreateValidator($request->all(), null);
        if($validator->fails()){
            return redirect()->back()->withInput($request->all())->withErrors($validator);
        }

        $request_data = $request->only(['email','name','no']);
        $request_data['password'] = bcrypt($request->input('password'));
        $analyst = $this->moduleRepository->create($request_data);
        $products_ids = $request->input('products', []);
        $analyst->products()->sync($products_ids);

        return $request->input('action')=="save_exit" ? redirect(url('/admin/'.str_plural($this->moduleName))) : redirect(url('/admin/'.str_plural($this->moduleName).'/'.$analyst->id.'/edit'));
    }
    public function grantStore(Request $request, $analyst_id)
    {
        $analyst = $this->moduleRepository->get($analyst_id);
        if(!$analyst){
             return redirect(url('/admin/'.str_plural($this->moduleName).'/'.$analyst->id.'/grants/create'));
        }
        $request_data = $request->only(['statement_no', 'year_month','price','handle_fee','platform_fee','income_tax','second_generation_nhi','interbank_remittance_fee']);

        if($analyst->grants()->where('year_month',$request_data['year_month'])->count()!=0){
           return redirect(url('/admin/'.str_plural($this->moduleName).'/'.$analyst->id.'/grants/create'));
        }
        $grant = $analyst->grants()->create($request_data);
        $extra_amounts = $request->input('grant_amounts', []);
        foreach ($extra_amounts as $key => $extra_amount) {
            $grant->details()->create(['name'=> $extra_amount['name'],'amount'=>$extra_amount['amount']]);
        }
        return $request->input('action')=="save_exit" ? redirect(url('/admin/'.str_plural($this->moduleName).'/'.$analyst_id.'/grants')) : redirect(url('/admin/'.str_plural($this->moduleName).'/'.$analyst->id.'/grants/'.$grant->id.'/edit'));
    }
    public function update(Request $request, $id=0)
    {
        if(!$id){
            return redirect()->back();
        }
        $validator = $this->analystUpdateValidator($request->all(), $id);
        if($validator->fails()){
            return redirect()->back()->withInput($request->all())->withErrors($validator);
        }

        $request_data = $request->only(['email','name','no']);
        if($request->input('password')){
            $request_data['password'] = bcrypt($request->input('password'));
        }
        $analyst = $this->moduleRepository->update($id, $request_data);

        $products_ids = $request->input('products', []);
        $analyst->products()->sync($products_ids);

        return $request->input('action')=="save_exit" ? redirect(url('/admin/'.str_plural($this->moduleName))) : redirect(url('/admin/'.str_plural($this->moduleName).'/'.$id.'/edit'));
    }
    public function grantUpdate(Request $request, $analyst_id, $id)
    {
        $analyst = $this->moduleRepository->get($analyst_id);
        if(!$analyst){
             return redirect(url('/admin/'.str_plural($this->moduleName).'/'.$analyst->id.'/grants/create'));
        }
        $request_data = $request->only(['statement_no', 'year_month','price','handle_fee','platform_fee','income_tax','second_generation_nhi','interbank_remittance_fee']);

        $analyst->grants()->where('id',$id)->update($request_data);
        $grant = $analyst->grants()->find($id);
        $extra_amounts = $request->input('grant_amounts', []);
        $extra_amount_ids = [];
        foreach ($extra_amounts as $key => $extra_amount) {
            $data = ['name'=> $extra_amount['name'],'amount'=>$extra_amount['amount']];
            if($extra_amount['id']=='0'){
                $grant_detail = $grant->details()->create($data);
            }else{
                $grant->details()->where('id',$extra_amount['id'])->update($data);
                $grant_detail = $grant->details()->find($extra_amount['id']);
            }
            array_push($extra_amount_ids, $grant_detail->id);
        }
        $grant->details()->whereNotIn('id',$extra_amount_ids)->delete();
        return $request->input('action')=="save_exit" ? redirect(url('/admin/'.str_plural($this->moduleName).'/'.$analyst_id.'/grants')) : redirect(url('/admin/'.str_plural($this->moduleName).'/'.$analyst->id.'/grants/'.$grant->id.'/edit'));
    }
    protected function analystCreateValidator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|max:255|unique:analysts,email',
            'password' => 'required|max:255',
        ]);        
    }
    protected function analystUpdateValidator(array $data,$id)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|max:255|unique:analysts,email'.($id?','.$id:'')
        ]);        
    }

    public function getAmounts(Request $request, $analyst_id)
    {
        $month = date('Y-m').'-01';
        $where= [
            'status' => 1,
            'price.<>'=>0
        ];
        $query_string=[];
        if($request->has('year_month')){
            $where['created_at.>=']=$request->input('year_month').'-01';
            $query_string['start_date'] =$request->input('year_month').'-01';
            $where['created_at.<']=date('Y-m-d',strtotime($request->input('year_month').'-01 next month -1 day'));
            $query_string['end_date'] = date('Y-m-d',strtotime($request->input('year_month').'-01 next month -1 day'));
        }
        $user = $this->moduleRepository->get($analyst_id);
        $products_ids = $user->products->map(function($item, $key){return $item->id;})->toArray();

        $orders = $this->orderRepository->getsWith(['products','promocodes'], $where, ['created_at'=>'DESC'], ['products'=>function($query) use ($products_ids){
                $query->whereIn('id',$products_ids);
            }]);
        $order_products = [];
        $sumOfOrderPrice = 0;
        $sumOfHandleFee = 0;
        $sumOfPlatformFee = 0;
        foreach ($orders as $key => $order) {
            foreach ($order->products as $key2 => $product) {
                if(in_array($product->id,$products_ids)){
                    $order_product = clone $order;
                    $order_product->product_name = $product->name;
                    $order_product->product_id = $product->id;
                    $offer = 0;
                    foreach ($order->promocodes as $key3 => $promocode){
                        if($promocode->products()->where('id',$product->id)->count())
                        {
                            $offer+=$promocode->offer;
                        }
                    }
                    $order_product->product_price = $product->pivot->unit_price * $product->pivot->quantity;
                    $order_product->order_price = $order_product->product_price<$offer ? 0:$order_product->product_price-$offer;
                    $order_product->handle_fee = round($this->getHandleFee($order->paymentType, $order_product->order_price, $order->created_at), 2);
                    $order_product->platform_fee = (($order_product->order_price/1.05) - $order_product->handle_fee)*0.3;
                    $order_product->platform_fee = $order_product->platform_fee < 0 ? 0 : $order_product->platform_fee;
                    $sumOfOrderPrice += $order_product->order_price;
                    $sumOfHandleFee += $order_product->handle_fee;
                    $sumOfPlatformFee += $order_product->platform_fee;
                    array_push($order_products, $order_product);
                }
            }
        }
        return response()->json(['price'=>floor($sumOfOrderPrice),'handle_fee'=>floor($sumOfHandleFee),'platform_fee'=>floor($sumOfPlatformFee)]);
    }
    private function getHandleFee($paymentType, $price, $date){
        switch ($paymentType) {
            case '':return 0;
            case 'credit':return ($price*0.0275<5) ? 5:( $price * 0.0275);
            case 'webatm':case 'atm':return ($price*0.01<5) ? 5:(($price*0.01>26)?26:($price*0.01));
            case 'cvs':return strtotime($date) < strtotime('2018-09-01')? 26:30;
            default:return 0;
        }
    }
    public function grantDestroy(Request $request, $analyst_id, $id = 0)
    {
        $analyst = $this->moduleRepository->get($analyst_id);
        $ids = $id ? [$id]:$request->input('id',[]);
        foreach ($ids as $key => $value) {
           $analyst->grants()->where('id',$value)->delete();
        }
        return $id ? redirect(url('/admin/'.str_plural($this->moduleName).'/'.$analyst_id.'/grants')):$this->successResponse(['id'=>$ids]);
    }
    public function details(Request $request,$analyst_id)
    {
        $month = date('Y-m').'-01';
        $where= [
            'status' => 1,
            'price.<>'=>0
        ];
        $query_string=[];
        if($request->has('year_month')){
            $where['created_at.>=']=$request->input('year_month').'-01';
            $query_string['start_date'] =$request->input('year_month').'-01';
            $where['created_at.<']=date('Y-m-d',strtotime($request->input('year_month').'-01 next month -1 day'));
            $query_string['end_date'] = date('Y-m-d',strtotime($request->input('year_month').'-01 next month -1 day'));
        }
        $analyst = $this->moduleRepository->get($analyst_id);
        $products_ids = $analyst->products->map(function($item, $key){return $item->id;})->toArray();

        $orders = $this->orderRepository->getsWith(['products','promocodes'], $where, ['created_at'=>'DESC'], ['products'=>function($query) use ($products_ids){
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
                    foreach ($order->promocodes as $key3 => $promocode){
                        if($promocode->products()->where('id',$product->id)->count())
                        {
                            $offer+=$promocode->offer;
                        }
                    }
                    $order_product->product_price = $product->pivot->unit_price * $product->pivot->quantity;
                    $order_product->order_price = $order_product->product_price<$offer ? 0:$order_product->product_price-$offer;
                    $order_product->handle_fee = round($this->getHandleFee($order->paymentType, $order_product->order_price, $order->created_at), 2);
                    $order_product->platform_fee = (($order_product->order_price/1.05) - $order_product->handle_fee)*0.3;
                    $order_product->platform_fee = $order_product->platform_fee < 0 ? 0 : $order_product->platform_fee;
                    array_push($order_products, $order_product);
                }
            }
        }

        $data = [
            'actionName'=>__FUNCTION__,
            'module_name'=> $this->moduleName,
            'subtitle'=>'detail_page',
            'actions'=>[],
            'analyst'=>$analyst,
            'tools'=>['date_range','options'=>false],
            'tabs'=>[],
            'query_string' => $query_string,
            'table_data' => $order_products,
            'table_head' =>['product_name', 'product_id', 'product_price','no','user_nickname','order_price','status','created_at','paymentType','handle_fee','platform_fee'],
            'table_formatter' =>['status','paymentType', 'platform_fee'],
            'table_action'=>false,
            'table_js'=>'order/table',
        ];
        return view('admin.analyst.grant_list',$data);
    }
}
