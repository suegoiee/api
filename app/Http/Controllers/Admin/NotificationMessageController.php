<?php

namespace App\Http\Controllers\Admin;
use App\Repositories\UserRepository;
use App\Repositories\NotificationMessageRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
class NotificationMessageController extends AdminController
{	
    protected $userRepository;
    public function __construct(Request $request, NotificationMessageRepository $notificationMessageRepository,UserRepository $userRepository)
    {
        parent::__construct($request);
        $this->moduleName='notificationMessage';
        $this->moduleRepository = $notificationMessageRepository;
        $this->userRepository = $userRepository;
        //$this->token = $this->clientCredentialsGrantToken();
    }

    public function index()
    {
        $notificationMessages = $this->moduleRepository->getsWith([],[],["updated_at"=>'DESC']);

        $data = [
            'actionName'=>__FUNCTION__,
            'module_name'=> $this->moduleName,
            'actions'=>['new'],
            'table_data' => $notificationMessages,
            'table_head' =>['id','title','created_at'],
            'table_formatter' =>[],
        ];
        return view('admin.list',$data);
    }
    public function show($id)
    {
        $notificationMessage = $this->moduleRepository->get($id);
        $data = [
            'actionName'=>__FUNCTION__,
            'module_name'=> $this->moduleName,
            'title_field'=> $notificationMessage->name,
            'data' => $notificationMessage,
        ];
        return view('admin.detail',$data);
    }
     public function create()
    {
        $data = [
            'actionName'=>__FUNCTION__,
            'module_name' => $this->moduleName,
            'users' => $this->userRepository->getsWith(['profile']),
            'data' => null,
            'send_actions'=>true
        ];
        return view('admin.form',$data);
    }
    
    public function edit($id)
    {
        $notificationMessage = $this->moduleRepository->get($id);
        $notificationMessage->user_ids = $notificationMessage->user_ids ? json_decode($notificationMessage->user_ids) : [];
        $data = [
            'actionName'=>__FUNCTION__,
            'module_name' => $this->moduleName,
            'users' => $this->userRepository->getsWith(['profile']),
            'data' => $notificationMessage,
            'send_actions'=>true
        ];

        return view('admin.form',$data);
    }

    public function export(Request $request)
    {
        $where['id.in'] = $request->ids;
        $data = $this->moduleRepository->getsWith([], $where, ["updated_at"=>'DESC'])->toArray();
        $sheet = [];
        $message = ['編號' => null, '標題' => null, '通知時間' => null];
        foreach ($data as $row) {
            foreach ($row as $key => $value) {
                switch ($key) {
                    case "id":
                        $message['編號'] = $value;
                        break;
                    case "title":
                        $message['標題'] = $value;
                        break;
                    case "created_at":
                        $message['通知時間'] = $value;
                        break;

                }
            }
            array_push($sheet, $message);
            $message = array_fill_keys(array_keys($message), null);
        }
        $this->tableExport($sheet);
    }
}
