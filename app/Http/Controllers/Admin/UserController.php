<?php

namespace App\Http\Controllers\Admin;

use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
class UserController extends AdminController
{	
    public function __construct(Request $request, UserRepository $userRepository)
    {
        parent::__construct($request);
        $this->moduleName='user';
        $this->moduleRepository = $userRepository;
        //$this->token = $this->clientCredentialsGrantToken();
    }

    public function index()
    {
        $users = $this->moduleRepository->getsWith();
        $data = [
            'actionName'=>__FUNCTION__,
            'module_name'=> $this->moduleName,
            'table_data' => $users,
            'table_head' =>['id','email','mail_verified_at','updated_at','created_at'],
            'table_formatter' =>['nickname','mail_verified_at'],
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
            'actionName'=>__FUNCTION__,
            'module_name'=> $this->moduleName,
            'data' => $this->moduleRepository->get($id),
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
