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

    public function index(Request $request)
    {
        $user = $request->user();
        $_notifications = $user->notifications;//->sortBy('read_at');
        $notifications = [];
        foreach ($_notifications as $key => $notification) {
            $type = explode("\\", $notification->type);
            $status = $notification->read_at ? 1 : 0 ;
            array_push($notifications,['id'=> $notification->id, 'type'=> $type[count($type)-1],'created_at'=> $notification->created_at->toDateTimeString(),'data'=>$notification->data,'status'=>$status]);
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
            array_push($notifications,['id'=> $notification->id, 'type'=> $type[count($type)-1],'created_at'=> $notification->created_at->toDateTimeString(),'data'=>$notification->data]);
        }
        return $this->successResponse($notifications);
    }

    public function update(Request $request, $id)
    {
        $now = Carbon::now();
        $user = $request->user();
        $notifications = $user->unreadNotifications()->where('id',$id)->update(['read_at' => $now]);
        return $this->successResponse(['id'=>$id,'read_at'=>$now->toDateTimeString()]);
    }
}
