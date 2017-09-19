<?php

namespace App\Http\Controllers\Admin;

use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
class UserController extends AdminController
{	
    public function __construct(UserRepository $userRepository)
    {
        $this->moduleName='user';
        $this->moduleRepository = $userRepository;
        $this->token = $this->clientCredentialsGrantToken();
    }

    public function index()
    {
        $users = $this->moduleRepository->getsWith(['profile']);
        foreach ($users as $key => $user) {
            $user->nick_name = $user->profile->nick_name;
        }
        $data = [
            'module_name'=> $this->moduleName,
            'table_data' => $users,
            'table_head' =>['id','email','nick_name'],
            'table_formatter' =>[],
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
            'data' => $this->moduleRepository->get($id),
        ];
        return view('admin.form',$data);
    }
}
