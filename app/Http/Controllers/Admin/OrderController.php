<?php
namespace App\Http\Controllers\Admin;
use App\Repositories\OrderRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Route;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Lang;

class OrderController extends AdminController
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
        $query_string=[];
        $query_data = [];
        if($request->has('free')){
            $where['price.='] = 0;
            $query_string = $request->only(['free']);
            $query_data['free'] = 1;
        }else if($request->has('status')){
            $where['status'] = $request->input('status',1);
            $where['price.<>'] = 0;
            $query_string = $request->only(['status']);
            $query_data['free'] = 0;
            $query_data['status'] = $where['status'];
        }else{
            $where['status'] = $request->input('status',1);
            $where['price.<>'] = 0;
            $query_string['status'] = $request->input('status',1);
            $query_data['free'] = 0;
            $query_data['status'] = $where['status'];
        }


        $orders = [];//$this->moduleRepository->getsWith(['user','products'], $where, ['created_at'=>'DESC']);

        $data = [
            'actionName'=>__FUNCTION__,
            'module_name'=> $this->moduleName,
            'actions'=>[],
            'tabs'=>['status'=>[1,0,5],'free'=>[0]],
            'query_string' => $query_string,
            'query_data' => $query_data,
            'server_side' => 'active',
            'table_data' => $orders,
            'table_head' =>['id','user.profile.nickname','user.email','price','products','use_invoice','status','created_at'],
            'table_sortable' =>['id','price','status','created_at'],
            'table_formatter' =>[ 'user.profile.nickname', 'user.email','use_invoice','status', 'products']
        ];
        return view('admin.list',$data);
    }

    public function data(Request $request)
    {
        $where=[];
        $orderBy=[];
        $with = ['products', 'user', 'user.profile'];
        $orders_by = $this->moduleRepository;
        $search_fields = ['price', 'created_at'];
        $search_relation_fields = ['user.profile.nickname','user.email', 'products.name'];
        $search = ""; 
        $free =  $request->input('free', 0);

        if($free == 1){
            $where['price.='] = 0;
        }else{
            $where['status'] = $request->input('status',1);
            $where['price.<>'] = 0;
        }

        if($request->has('sort')){
            $order_column = $request->input('sort', false);
            $order = $request->input('order');
            if($order_column == 'products'){
                $orderBy['products.name'] = $order;
            }else if($order_column){
                $orderBy[$order_column] = $order;
            }
        }else{
            $order_column = $request->input('sort');
            $order = $request->input('order');
            $orderBy['created_at'] = 'DESC';
        }

        $offset = $request->input('offset',0);
        $limit = $request->input('limit',100);

        if($request->has('search') && $request->input('search','')!=''){
            $search = $request->input('search','');
        }
        $orders_total = $orders_by->whereBy($where)->toCount();
        $orders_total_filtered = $orders_by->searchBy( $search_fields, $search, $search_relation_fields)->whereBy($where)->orderBy($orderBy)->toCount();

        $orders = $orders_by->toWith($with)->searchBy( $search_fields, $search, $search_relation_fields)->whereBy($where)->orderBy($orderBy)->limit($offset, $limit)->toGets();
        
        //$orders = $this->moduleRepository->getsWith([], $where, ['created_at'=>'DESC']);
        $data = [
            "total" => $orders_total_filtered,
            "totalNotFiltered" => $orders_total,
            "rows" => $orders
        ];
        return response()->json($data);
    }

    public function create()
    {
        $data = [
            'actionName'=>__FUNCTION__,
            'module_name'=> $this->moduleName,
            'data'=>null,
        ];
        return view('admin.form',$data);
    }

    public function edit($id)
    {
        $order =  $this->moduleRepository->getWith($id,['products']);
        $ecpay = $order->ecpays()->orderBy('id','desc')->first();
        $order->MerchantTradeNo = $ecpay ? $ecpay->MerchantTradeNo: '';
        $data = [
            'actionName'=>__FUNCTION__,
            'module_name'=> $this->moduleName,
            'data' => $order,
        ];
        return view('admin.form',$data);
    }
    public function update(Request $request, $id)
    {
        $tokenRequest = $request->create(
            url('/user/'.str_plural($this->moduleName).'/'.$id),
            'put'
        );
        $tokenRequest->request->add($request->all());
        $tokenRequest->headers->set('Accept','application/json');
        $tokenRequest->headers->set('Authorization','Bearer '.isset($this->token['access_token'])? $this->token['access_token']:'');
        $instance = Route::dispatch($tokenRequest);
        $response_data = json_decode($instance->getContent(), true);
        return $this->adminResponse($request,$response_data);
    }
    public function cancel(Request $request, $id)
    {
        $tokenRequest = $request->create(
            url('/user/'.str_plural($this->moduleName).'/'.$id.'/cancel'),
            'put'
        );
        $tokenRequest->request->add($request->all());
        $tokenRequest->headers->set('Accept','application/json');
        $tokenRequest->headers->set('Authorization','Bearer '.isset($this->token['access_token'])? $this->token['access_token']:'');
        $instance = Route::dispatch($tokenRequest);
        $response_data = json_decode($instance->getContent(), true);
        return redirect(url('/admin/'.str_plural($this->moduleName)));
    }

    public function export(Request $request)
    {
        $where['id.in'] = $request->ids;
        $data = $this->moduleRepository->getsWith(['user', 'products'], $where, ['created_at'=>'DESC'])->toArray();
        $sheet = [];
        $order = ['訂單編號' => null, '訂購者' => null, '金額' => null, '付款狀態' => null, '訂購時間' => null, '購買人姓名' => null, '購買人電話' => null, '購買人地址' => null, '更改地址' => null, 'Email' => null, '使用發票'=>null, '發票'=>null];
        //$productColsHeader = ['產品' => null, '編號' => null, '名稱' => null, '類型' => null, '價格' => null];
        foreach ($data as $row) {
            $products = $row['products'];
            foreach ($row as $key => $value) {
                switch ($key) {
                    case "no":
                        $order['訂單編號'] = $value;
                        break;
                    case "user_nickname":
                        $order['訂購者'] = $value;
                        break;
                    case "price":
                        $order['金額'] = $value;
                        break;
                    case "status":
                        $order['付款狀態'] = Lang::get('order.admin.status_'.$value);
                        break;
                    case "created_at":
                        $order['訂購時間'] = $value;
                        break;
                    case "invoice_name":
                        $order['購買人姓名'] = $value;
                        break;
                    case "invoice_phone":
                        $order['購買人電話'] = $value;
                        break;
                    case "invoice_address":
                        $order['購買人地址'] = $value;
                        break;
                    case "user_email":
                        $order['Email'] = $value;
                        break;
                    case "use_invoice":
                        $order['使用發票'] = $value=='1' ? '紙本':'電子';
                        break;
                    case "invoice_type":
                        $order['發票'] = $value=='0' ? '捐贈': ($value=='1' ? '二聯':'三聯');
                        break;
                }
                $order['產品']  =  '';
                foreach ($products as $key => $product) {
                    $order['產品'] .= $product['name']." (".$product['quantity'].")\n";
                }
            }
            array_push($sheet, $order);
            $order = array_fill_keys(array_keys($order), null);
        }
        $this->tableExport($sheet);
    }
}
