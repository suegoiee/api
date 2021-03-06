<?php

namespace App\Http\Controllers\Admin;

use App\Repositories\UserRepository;
use App\Repositories\CategoryRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
class UserController extends AdminController
{	
    public function __construct(Request $request, UserRepository $userRepository, CategoryRepository $categoryRepository)
    {
        parent::__construct($request);
        $this->moduleName='user';
        $this->moduleRepository = $userRepository;
        $this->categoryRepository = $categoryRepository;
        //$this->token = $this->clientCredentialsGrantToken();
    }

    public function index()
    {
        $users = [];//$this->moduleRepository->getsWith();
        $data = [
            'actionName'=>__FUNCTION__,
            'module_name'=> $this->moduleName,
            'server_side' => 'active',
            'table_data' => $users,
            'table_head' =>['id', 'email', 'mail_verified_at', 'updated_at', 'created_at'],
            'table_formatter' =>['nickname', 'mail_verified_at'],
        ];
        return view('admin.list',$data);
    }

    public function data(Request $request)
    {
        $query_string = [];
        $query_data = [];
        $orderBy=[];
        $where=[];
        $module_by = $this->moduleRepository;
        $search_fields = ['id','email', 'updated_at', 'created_at'];
        $search_relation_fields = [];
        $search = ""; 

        if($request->has('sort')){
            $order_column = $request->input('sort', false);
            $order = $request->input('order');
            if($order_column){
                $orderBy[$order_column] = $order;
            }
        }

        $offset = $request->input('offset',0);
        $limit = $request->input('limit',100);

        if($request->has('search') && $request->input('search','')!=''){
            $search = $request->input('search','');
        }
        $module_total = $module_by->whereBy($where)->toCount();
        $module_total_filtered = $module_by->searchBy( $search_fields, $search, $search_relation_fields)->whereBy($where)->orderBy($orderBy)->toCount();
        $module = $module_by->searchBy( $search_fields, $search, $search_relation_fields)->whereBy($where)->orderBy($orderBy)->limit($offset, $limit)->toGets();
        
        //$orders = $this->moduleRepository->getsWith([], $where, ['created_at'=>'DESC']);
        $data = [
            "total" => $module_total_filtered,
            "totalNotFiltered" => $module_total,
            "rows" => $module
        ];
        return response()->json($data);
    }

    public function create()
    {
        $data = [
            'module_name'=> $this->moduleName,
            'categories'=> $this->categoryRepository->gets(),
            'data'=>null,
        ];
        return view('admin.form',$data);
    }

    public function edit($id)
    {
        $data = [
            'actionName'=>__FUNCTION__,
            'module_name'=> $this->moduleName,
            'categories'=> $this->categoryRepository->gets(),
            'data' => $this->moduleRepository->getWith($id, ['permissions']),
        ];
        return view('admin.form',$data);
    }
    public function show($id)
    {
        $user = $this->moduleRepository->get($id);
        $data = [
            'actionName'=>__FUNCTION__,
            'module_name'=> $this->moduleName,
            'title_field'=> $user->profile->nick_name,
            'data' => $user,
        ];
        return view('admin.detail',$data);
    }
    public function export(Request $request)
    {
        $where['id.in'] = $request->ids;
        $data = $this->moduleRepository->getsWith(['profile'], $where)->toArray();
        $sheet = [];
        $user = ['編號' => null, '暱稱' => null, 'Email' => null, '驗證' => null];
        foreach ($data as $row) {
            $products = [];
            foreach ($row as $key => $value) {
                switch ($key) {
                    case "id":
                        $user['編號'] = $value;
                        break;
                    case "profile":
                        $user['暱稱'] = $value ? $value['nickname'] : '';
                        break;
                    case "email":
                        $user['Email'] = $value;
                        break;
                    case "mail_verified_at":
                        $user['驗證'] = $value;
                        break;
                    default:break;
                }
            }
            array_push($sheet, $user);
            $user = array_fill_keys(array_keys($user), null);
        }
        $this->tableExport($sheet);
    }
}
