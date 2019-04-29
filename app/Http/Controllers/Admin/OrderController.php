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
        if($request->has('free')){
            $where['price.='] = 0;
            $query_string = $request->only(['free']);
        }else if($request->has('status')){
            $where['status'] = $request->input('status',1);
            $where['price.<>'] = 0;
            $query_string = $request->only(['status']);
        }else{
            $where['status'] = $request->input('status',1);
            $where['price.<>'] = 0;
            $query_string['status'] = $request->input('status',1);
        }

        $orders = $this->moduleRepository->getsWith(['user','products'], $where, ['created_at'=>'DESC']);

        $data = [
            'actionName'=>__FUNCTION__,
            'module_name'=> $this->moduleName,
            'actions'=>[],
            'tabs'=>['status'=>[1,0,5],'free'=>[0]],
            'query_string' => $query_string,
            'table_data' => $orders,
            'table_head' =>['no',/*'user_nickname','user_email',*/'price','status','created_at'],
            'table_formatter' =>['user_email','status'],
        ];
        return view('admin.list',$data);
    }

    public function test(Request $request)
    {
        $query_string=[];
        if($request->has('free')){
            $where['price.='] = 0;
            $query_string = $request->only(['free']);
        }else if($request->has('status')){
            $where['status'] = $request->input('status',1);
            $where['price.<>'] = 0;
            $query_string = $request->only(['status']);
        }else{
            $where['status'] = $request->input('status',1);
            $where['price.<>'] = 0;
            $query_string['status'] = $request->input('status',1);
        }

        $orders = $this->moduleRepository->getsWith([], $where, ['created_at'=>'DESC']);

        $data = [
            'actionName'=>__FUNCTION__,
            'module_name'=> $this->moduleName,
            'actions'=>[],
            'tabs'=>['status'=>[1,0,5],'free'=>[0]],
            'query_string' => $query_string,
            'table_data' => $orders,
            'table_head' =>['no','price','status','created_at'],
            'table_formatter' =>['status'],
        ];
        return view('admin.list',$data);
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

        $data = [
            'actionName'=>__FUNCTION__,
            'module_name'=> $this->moduleName,
            'data' => $this->moduleRepository->getWith($id,['products']),
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
        $order = ['訂單編號' => null, '訂購者' => null, '金額' => null, '付款狀態' => null, '訂購時間' => null, '購買人姓名' => null, '購買人電話' => null, '購買人地址' => null, '更改地址' => null, 'Email' => null];
        //$productColsHeader = ['產品' => null, '編號' => null, '名稱' => null, '類型' => null, '價格' => null];
        foreach ($data as $row) {
            $products = [];
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
                }
            }
            array_push($sheet, $order);
            $order = array_fill_keys(array_keys($order), null);
        }
        $this->tableExport($sheet);
    }
}
