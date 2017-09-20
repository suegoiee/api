<?php
namespace App\Http\Controllers\Admin;
use App\Repositories\OrderRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
class OrderController extends AdminController
{	
    public function __construct(OrderRepository $orderRepository)
    {
        $this->moduleName='order';
        $this->moduleRepository = $orderRepository;

        $this->token = $this->clientCredentialsGrantToken();
    }

    public function index(Request $request)
    {
        $query_string=['status' => 0];
        if($request->has('status')){
            $query_string['status']=$request->input('status');
        }
        $orders = $this->moduleRepository->getsWith(['user','products'],$query_string);
        $data = [
            'module_name'=> $this->moduleName,
            'actions'=>[],
            'tabs'=>['status'=>[0,1]],
            'query_string' => $query_string,
            'table_data' => $orders,
            'table_head' =>['id','user_nick_name','price','status','created_at'],
            'table_formatter' =>['user_nick_name','status'],
        ];
        return view('admin.list',$data);
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
}
