<?php 
namespace App\Traits;
use Route;

trait ResponseFormatter
{
    protected function successResponse($data=[],$message=''){
        $uri = Route::current()->uri();
        $actionMethod = Route::current()->methods()[0];
        return response()->json(['status'=>'success','data'=>$data, 'uri'=>$uri, 'method'=>$actionMethod]);
    }
    protected function failedResponse($error=[],$data=[],$message=''){
        $uri = Route::current()->uri();
        $actionMethod = Route::current()->methods()[0];
        return response()->json(['status'=>'error','data'=>$data,'message'=>$message,'error'=>$error, 'uri'=>$uri, 'method'=>$actionMethod]);
    }
    protected function validateErrorResponse($message=''){
        $uri = Route::current()->uri();
        $actionMethod = Route::current()->methods()[0];
        return response()->json(['status'=>'error','error'=>['message'=>$message], 'uri'=>$uri, 'method'=>$actionMethod]);
    }
    protected function unauthorizedResponse(){
        $uri = Route::current()->uri();
        $actionMethod = Route::current()->methods()[0];
    	return response()->json(['status'=>'error','error'=>['message'=>['unauthorized']], 'uri'=>$uri, 'method'=>$actionMethod]);	
    }
    protected function notFoundResponse(){
        $uri = Route::current()->uri();
        $actionMethod = Route::current()->methods()[0];
        return response()->json(['status'=>'error','error'=>['message'=>['Not Found']], 'uri'=>$uri, 'method'=>$actionMethod]);
    }
}