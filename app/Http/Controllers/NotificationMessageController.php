<?php

namespace App\Http\Controllers;
use App\Repositories\UserRepository;
use App\Repositories\NotificationMessageRepository;
use App\Notifications\Others;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Mail\Announcement;
use Illuminate\Support\Facades\Mail;

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

        $request_data = $request->only(['title', 'content', 'type']);
        $request_data['send_email'] = $request->input('send_email', 0);
        $request_data['send_notice'] = $request->input('send_notice', 0);
        $all_user = $request->input('all_user', 0);
        $classType = 'App\\Notifications\\'.$request->input('type', 'Others');
        $notificationType = $request->input('type', 'Others');
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
                    $this->userRepository->getsWith([],['mail_verified_at.<>'=>null,'id.in'=>$user_ids]) : 
                    $this->userRepository->getsWith([],['mail_verified_at.<>'=>null]) ;

        if($notificationType=='MassiveAnnouncement'){
            $bcc = [];
            foreach ($users as $key => $user) {
                if($user->subscription==1){
                    array_push($bcc, $user->email);
                }
            }
            $n = 0;
            $div  =  250;
            while($n < count($bcc)){
                Mail::to(env('APP_EMAIL','service@uanalyze.com.tw'))->bcc(array_slice($bcc, $n, $div))->queue(new Announcement($notificationMessage));
                $n+=$div;
            }
        }else{
            foreach ($users as $key => $user) { 
                $user->notify(new $classType($user, $notificationMessage));
            }
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

        $request_data = $request->only(['title', 'content', 'type']);
        $request_data['send_email'] = $request->input('send_email', 0);
        $request_data['send_notice'] = $request->input('send_notice', 0);
        $all_user = $request->input('all_user', 0);
        $classType = 'App\\Notifications\\'.$request->input('type', 'Others');
        $notificationType = $request->input('type', 'Others');
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
                    $this->userRepository->getsWith([],['mail_verified_at.<>'=>null,'id.in'=>$user_ids,'subscription'=>1]) : 
                    $this->userRepository->getsWith([],['mail_verified_at.<>'=>null,'subscription'=>1]) ;
        if($notificationType=='MassiveAnnouncement'){
            $bcc = [];
            foreach ($users as $key => $user) {
                if($user->subscription==1){
                    array_push($bcc, $user->email);
                }
            }
            $n = 0;
            $div  =  250;
            while($n < count($bcc)){
                Mail::to(env('APP_EMAIL','service@uanalyze.com.tw'))->bcc(array_slice($bcc, $n, $div))->queue(new Announcement($notificationMessage));
                $n+=$div;
            }
        }else{
            foreach ($users as $key => $user) { 
                $user->notify(new $classType($user, $notificationMessage));
            }
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
            'title'=>'required',
            'content'=>'required'
        ]);        
    }
}
