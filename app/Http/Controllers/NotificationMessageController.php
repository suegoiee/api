<?php

namespace App\Http\Controllers;
use App\Repositories\UserRepository;
use App\Repositories\NotificationMessageRepository;
use App\Notifications\ReceiveMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NotificationMessageController extends Controller
{	
    protected $notificationMessageRepository;
    protected $userRepository;
    public function __construct(NotificationMessageRepository $notificationMessageRepository, UserRepository $userRepository)
    {
	   $this->notificationMessageRepository = $notificationMessageRepository;
       $this->userRepository = $userRepository;
    }

    public function index()
    {
        $notificationMessages = $this->notificationMessageRepository->gets();
        return $this->successResponse($notificationMessages);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $validator = $this->notificationMessageValidator($request->all());
        if($validator->fails()){
            return $this->validateErrorResponse($validator->errors()->all());
        }

        $request_data = $request->only(['content']);
        $request_data['send_email'] = $request->input('send_email', 0);
        $all_user = $request->input('all_user', 0);
        if(!$all_user){
            $user_ids = $request->input('user_ids',[]);
            if(count($user_ids)>0){
                $request_data['user_ids'] = json_encode($user_ids, true);
            }else{
                $request_data['user_ids'] = NULL;
            }
        }else{
            $user_ids = [];
            $request_data['user_ids'] = NULL;
        }
        $notificationMessage = $this->notificationMessageRepository->create($request_data);
        $users = count($user_ids) > 0 ? 
                    $this->userRepository->getsWith([],['id.in'=>$user_ids]) : 
                    $this->userRepository->gets() ;
        foreach ($users as $key => $user) {
            $user->notify(new ReceiveMessage($user, $notificationMessage->content, $notificationMessage->send_email));
        }

        return $this->successResponse($notificationMessage?$notificationMessage:[]);
    }

    public function show(Request $request, $id)
    {
        $notificationMessage = $this->notificationMessageRepository->get($id);

        return $this->successResponse($notificationMessage?$notificationMessage:[]);
    }

    public function edit($id)
    {
      
    }

    public function update(Request $request, $id)
    {
        $validator = $this->notificationMessageValidator($request->all());
        if($validator->fails()){
            return $this->validateErrorResponse($validator->errors()->all());
        }

        $request_data = $request->only(['content']);
        $request_data['send_email'] = $request->input('send_email', 0);
        $all_user = $request->input('all_user', 0);
        if(!$all_user){
            $user_ids = $request->input('user_ids',[]);
            if(count($user_ids)>0){
                $request_data['user_ids'] = json_encode($user_ids, true);
            }else{
                $request_data['user_ids'] = NULL;
            }
        }else{
            $user_ids = [];
            $request_data['user_ids'] = NULL;
        }

        $notificationMessage = $this->notificationMessageRepository->update($id,$request_data);
        
        $users = count($user_ids) > 0 ? 
                    $this->userRepository->getsWith([],['id.in'=>$user_ids]) : 
                    $this->userRepository->gets() ;
        foreach ($users as $key => $user) {
            $user->notify(new ReceiveMessage($user, $notificationMessage->content, $notificationMessage->send_email));
        }
        return $this->successResponse($notificationMessage?$notificationMessage:[]);
    }

    public function destroy(Request $request, $id)
    {
        $this->notificationMessageRepository->delete($id);
        return $this->successResponse(['id'=>$id]);
    }

    protected function notificationMessageValidator(array $data)
    {
        return Validator::make($data, [
            'content'=>'required'
        ]);        
    }
}