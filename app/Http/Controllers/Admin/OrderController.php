<?php
namespace App\Http\Controllers\Admin;
use App\Repositories\OrderRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Route;
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
            $where['status'] = $request->input('status',0);
            $where['price.<>'] = 0;
            $query_string = $request->only(['status']);
        }else{
            $where['status'] = $request->input('status',0);
            $where['price.<>'] = 0;
            $query_string['status'] = $request->input('status',0);
        }

        $orders = $this->moduleRepository->getsWith(['user','user.profile','products'], $where, ['created_at'=>'DESC']);

        $data = [
            'actionName'=>__FUNCTION__,
            'module_name'=> $this->moduleName,
            'actions'=>[],
            'tabs'=>['status'=>[0,1]],
            'query_string' => $query_string,
            'table_data' => $orders,
            'table_head' =>['no','user_nickname','price','status','created_at'],
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
}
