<?php

namespace App\Http\Controllers\Admin;

use App\Repositories\AnalystRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
class AnalystController extends AdminController
{	
    public function __construct(Request $request, AnalystRepository $analystRepository)
    {
        parent::__construct($request);
        $this->moduleName='analyst';
        $this->moduleRepository = $analystRepository;
        //$this->token = $this->clientCredentialsGrantToken();
    }

    public function index()
    {
        $users = $this->moduleRepository->getsWith();
        $data = [
            'module_name'=> $this->moduleName,
            'actions'=>['new'],
            'table_data' => $users,
            'table_head' =>['id','email','name','updated_at'],
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
    public function show($id)
    {
        $user = $this->moduleRepository->get($id);
        $data = [
            'module_name'=> $this->moduleName,
            'title_field'=> $user->profile->nick_name,
            'data' => $user,
        ];
        return view('admin.detail',$data);
    }
    public function store(Request $request)
    {
        $validator = $this->analystCreateValidator($request->all(), null);
        if($validator->fails()){
            return redirect()->back()->withInput($request->all())->withErrors($validator);
        }

        $request_data = $request->only(['email','name']);
        $request_data['password'] = bcrypt($request->input('password'));
        $product = $this->productRepository->create($request_data);
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

        $request_data = $request->only(['email','name']);
        if($request->input('password')){
            $request_data['password'] = bcrypt($request->input('password'));
        }
        $product = $this->productRepository->update($request_data);
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
}
