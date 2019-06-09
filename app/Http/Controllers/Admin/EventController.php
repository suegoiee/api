<?php
namespace App\Http\Controllers\Admin;
use App\Repositories\EventRepository;
use App\Repositories\ProductRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Route;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Lang;

class EventController extends AdminController
{   
    protected $productRepository;
    public function __construct(Request $request, EventRepository $eventRepository, ProductRepository $productRepository)
    {
        parent::__construct($request);
        $this->moduleName='event';
        $this->moduleRepository = $eventRepository;
        $this->productRepository = $productRepository;

        //$this->token = $this->clientCredentialsGrantToken();
    }

    public function index(Request $request)
    {
        $query_string=[];
        $query_data = [];
        $status = 1;

        if($request->has('status')){
            $status = $request->input('status',1);
        }
        
        $where['status'] =  $status;
        $query_string['status'] = $status;
        $query_data['status'] = $status;


        $events = [];

        $data = [
            'actionName'=>__FUNCTION__,
            'module_name'=> $this->moduleName,
            'actions'=>['new'],
            'tabs'=>['status'=>[1, 0, 2]],
            'query_string' => $query_string,
            'query_data' => $query_data,
            'server_side' => 'active',
            'table_data' => $events,
            'table_head' =>['name', 'started_at', 'ended_at', 'created_at'],
            'table_sortable' =>['name', 'started_at', 'ended_at', 'created_at'],
            'table_formatter' =>[ 'started_at', 'ended_at'],
        ];
        return view('admin.list',$data);
    }

    public function data(Request $request)
    {
        $where=[];
        $orderBy=[];
        $with = [];
        $events_by = $this->moduleRepository;
        $search_fields = ['name', 'started_at', 'ended_at', 'created_at'];
        $search_relation_fields = [];
        $search = ""; 
        $status = 1;
        
        if($request->has('status')){
            $status = $request->input('status',1);
        }

        $where['status'] = $status;

        if($request->has('sort')){
            $order_column = $request->input('sort');
            $order = $request->input('order');
            $orderBy[$order_column] = $order;
        }else{
            $order_column = $request->input('sort');
            $order = $request->input('order');
            $orderBy['created_at'] = 'DESC';
        }

        $offset = $request->input('offset',0);
        $limit = $request->input('limit',100);

        if($request->has('search') && $request->input('search','')!=''){
            unset($where['status']);
            $search = $request->input('search','');
        }
        $events_total = $events_by->whereBy($where)->toCount();
        $events_total_filtered = $events_by->searchBy( $search_fields, $search, $search_relation_fields)->whereBy($where)->orderBy($orderBy)->toCount();

        $events = $events_by->toWith($with)->searchBy( $search_fields, $search, $search_relation_fields)->whereBy($where)->orderBy($orderBy)->limit($offset, $limit)->toGets();
        
        $data = [
            "total" => $events_total_filtered,
            "totalNotFiltered" => $events_total,
            "rows" => $events
        ];
        return response()->json($data);
    }

    public function create()
    {
        $products = $this->productRepository->getsWith([],[],['status'=>'DESC']);
        $data = [
            'actionName'=>__FUNCTION__,
            'module_name'=> $this->moduleName,
            'products'=>$products,
            'data'=>null,
        ];
        return view('admin.form',$data);
    }

    public function edit($id)
    {

        $products = $this->productRepository->getsWith([],[],['status'=>'DESC']);
        $event = $this->moduleRepository->get($id);
        $condition_products = $event->condition_products;
        $bonus_products = $event->products;
        $data = [
            'actionName'=>__FUNCTION__,
            'module_name'=> $this->moduleName,
            'products'=>$products,
            'condition_products'=>$condition_products,
            'bonus_products'=>$bonus_products,
            'data' => $this->moduleRepository->getWith($id,['products']),
        ];
        return view('admin.form',$data);
    }
}
