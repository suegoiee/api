<?php 
namespace App\Traits;
use Request;
trait ResponseFormatter
{
    protected function successResponse($data=[],$message=''){
        $uri = $this->getUri();
        $actionMethod = $this->getMethod();
        return response()->json(['status'=>'success','data'=>$data, 'uri'=>$uri, 'method'=>$actionMethod]);
    }
    protected function failedResponse($error=[],$data=[],$message=''){
        $uri = $this->getUri();
        $actionMethod = $this->getMethod();
        return response()->json(['status'=>'error','data'=>$data,'error'=>$error, 'uri'=>$uri, 'method'=>$actionMethod]);
    }
    protected function validateErrorResponse($message=''){
        $uri = $this->getUri();
        $actionMethod = $this->getMethod();
        return response()->json(['status'=>'error','error'=>['message'=>$message, 'code'=>'E30001'], 'uri'=>$uri, 'method'=>$actionMethod]);
    }
    protected function unauthorizedResponse(){
        $uri = $this->getUri();
        $actionMethod = $this->getMethod();
    	return response()->json(['status'=>'error','error'=>['message'=>['unauthorized']], 'uri'=>$uri, 'method'=>$actionMethod]);	
    }
    protected function notFoundResponse(){
        $uri = $this->getUri();
        $actionMethod = $this->getMethod();
        return response()->json(['status'=>'error','error'=>['message'=>['Not Found']], 'uri'=>$uri, 'method'=>$actionMethod]);
    }
    private function getUri(){
        return Request::path();
    }
    private function getMethod(){
        return Request::method();
    }
}