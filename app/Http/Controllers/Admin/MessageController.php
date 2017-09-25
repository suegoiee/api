<?php

namespace App\Http\Controllers\Admin;

use App\Repositories\MessageRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
class MessageController extends AdminController
{	
    public function __construct(MessageRepository $messageRepository)
    {
        $this->moduleName='message';
        $this->moduleRepository = $messageRepository;
        $this->token = $this->clientCredentialsGrantToken();
    }

    public function index()
    {
        $messages = $this->moduleRepository->gets();

        $data = [
            'module_name'=> $this->moduleName,
            'actions'=>[],
            'table_data' => $messages,
            'table_head' =>['id','name','email','category','created_at'],
            'table_formatter' =>[],
        ];
        return view('admin.list',$data);
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
