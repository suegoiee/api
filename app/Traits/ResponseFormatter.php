<?php 
namespace App\Traits;

trait ResponseFormatter
{
    protected function successResponse($data=[],$message=''){
        return response()->json(['status'=>'success','data'=>$data,'message'=>$message]);
    }
    protected function failedResponse($error=[],$data=[],$message=''){
        return response()->json(['status'=>'error','data'=>$data,'message'=>$message,'error'=>$error]);
    }
    protected function validateErrorResponse($message=''){
        return response()->json(['status'=>'error','error'=>['message'=>$message]]);
    }
    protected function unauthorizedResponse(){
    	return response()->json(['status'=>'error','error'=>['message'=>['unauthorized']]]);	
    }
    protected function notFoundResponse(){
        return response()->json(['status'=>'error','error'=>['message'=>['Not Found']]]);
    }
}