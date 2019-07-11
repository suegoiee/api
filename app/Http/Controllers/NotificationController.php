<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NotificationController extends Controller
{	
    public function __construct()
    {
	   
    }

    public function read(Request $request)
    {
        $user = $request->user();
        $_notifications = $user->notifications;//->sortBy('read_at');
        $notifications = [];
        foreach ($_notifications as $key => $notification) {
            $type = explode("\\", $notification->type);
            $status = $notification->read_at ? 1 : 0 ;
            $data = $notification->data;
            if(!isset($data['content'])){
                $data['content'] = '';
            }
            if(!isset($data['title'])){
                $data['title'] = '';
            }
            if(isset($data['content']) && !is_string($data['content'])){
                $data['content'] = $data['content']['content'];
                $data['title'] = isset($data['content']['title']) ? $data['content']['title']:'';
            }
            if(isset($data['products'])){
                unset($data['products']);
            }
            if(isset($data['promocodes'])){
                unset($data['promocodes']);
            }
            if($type[count($type)-1] == 'ProductReceive' && isset($data['content'])){
               $data['content'] = str_replace('/e-com', 'e-com', $data['content']);
               $data['content'] = str_replace('/profile', 'profile', $data['content']);
            }
            array_push($notifications,['id'=> $notification->id, 'type'=> $type[count($type)-1],'created_at'=> $notification->created_at->toDateTimeString(),'data'=>$data, 'status'=>$status]);
        }
        return $this->successResponse($notifications);
    }
    public function unRead(Request $request)
    {
        $user = $request->user();
        $_notifications = $user->unreadNotifications;
        $notifications = [];
        foreach ($_notifications as $key => $notification) {
            $type = explode("\\", $notification->type);
            array_push($notifications,['id'=> $notification->id, 'type'=> $type[count($type)-1],'created_at'=> $notification->created_at->toDateTimeString(),'data'=>$notification->data, 'status'=>0]);
        }
        return $this->successResponse($notifications);
    }

    public function markRead(Request $request, $id)
    {
        $now = Carbon::now();
        $user = $request->user();
        $notifications = $user->unreadNotifications()->where('id',$id)->update(['read_at' => $now]);
        return $this->successResponse(['id'=>$id,'status'=>1,'read_at'=>$now->toDateTimeString()]);
    }
}
