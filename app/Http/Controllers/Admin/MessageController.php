<?php

namespace App\Http\Controllers\Admin;

use App\Repositories\MessageRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
class MessageController extends AdminController
{	
    public function __construct(Request $request, MessageRepository $messageRepository)
    {
        parent::__construct($request);
        $this->moduleName='message';
        $this->moduleRepository = $messageRepository;
        //$this->token = $this->clientCredentialsGrantToken();
    }

    public function index(Request $request)
    {
        $query_string=[];
        $where=[];
        if($request->has('status')){
            $where['status'] = $request->input('status',0);
            $query_string['status'] = $request->input('status',0);
        }else{
            $where['status'] = $request->input('status',0);
            $query_string['status'] = $request->input('status',0);
        }
        $messages = $this->moduleRepository->getsWith([],$where,['created_at'=>'DESC']);

        $data = [
            'module_name'=> $this->moduleName,
            'actions'=>[],
            'tabs'=>['status'=>[0,1]],
            'query_string'=>$query_string,
            'table_data' => $messages,
            'table_head' =>['id','name','email','category','created_at'],
            'table_formatter' =>[],
        ];
        return view('admin.list',$data);
    }
    public function edit($id)
    {
        return redirect('admin/messages/'.$id);
    }
    public function show($id)
    {
        $message = $this->moduleRepository->get($id);
        $data = [
            'module_name'=> $this->moduleName,
            'title_field'=> $message->name,
            'data' => $message,
        ];
        return view('admin.detail',$data);
    }

}
