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
        if(isset($error['message'])){
            $error['code'] = $this->getErrorCode($error['message']);
            $error['message']= [$error['message'][0]];
        }
        return response()->json(['status'=>'error','data'=>$data,'error'=>$error, 'uri'=>$uri, 'method'=>$actionMethod]);
    }
    protected function validateErrorResponse($message=[]){
        $uri = $this->getUri();
        $actionMethod = $this->getMethod();
        $errorCode = $this->getErrorCode($message);
        return response()->json(['status'=>'error','error'=>['message'=>[$message[0]], 'code'=>$errorCode], 'uri'=>$uri, 'method'=>$actionMethod]);
    }
    protected function unauthorizedResponse(){
        $uri = $this->getUri();
        $actionMethod = $this->getMethod();
        $errorCode = $this->getErrorCode(['unauthorized']);
    	return response()->json(['status'=>'error','error'=>['message'=>['unauthorized'],'code'=>$errorCode], 'uri'=>$uri, 'method'=>$actionMethod]);	
    }
    protected function notFoundResponse(){
        $uri = $this->getUri();
        $actionMethod = $this->getMethod();
        $errorCode = $this->getErrorCode(['Not Found']);
        return response()->json(['status'=>'error','error'=>['message'=>['Not Found'],'code'=>$errorCode], 'uri'=>$uri, 'method'=>$actionMethod]);
    }
    private function getUri(){
        return Request::path();
    }
    private function getMethod(){
        return Request::method();
    }
    private function getErrorCode($messages=[])
    {
        foreach ($messages as $key => $message) {
            switch ($message) {default:return 'E400100';
                //register
                case 'The selected email is invalid.':                      return 'E400009';
                case 'The user credentials were incorrect.':                return 'E400007';
                case 'The email field is required.':                        return 'E400101';
                case 'The email has already been taken.':                   return 'E400102';
                case 'The email must be a valid email address.':            return 'E400103';
                case 'The email must be a string.':                         return 'E400104';
                case 'The email may not be greater than 255 characters.':   return 'E400105';
                case 'The password field is required.':                     return 'E400106';
                case 'The password must be at least 6 characters.':         return 'E400107';
                case 'The password must be a string.':                      return 'E400108';
                case 'The nickname field is required.':                     return 'E400109';
                case 'The name may not be greater than 255 characters.':    return 'E400110';
                case 'The selected sex is invalid.':                        return 'E400111';
                case 'The birthday is not a valid date.':                   return 'E400112';
                case 'The refresh token field is required.':                return 'E400113';
                case 'The refresh token is invalid.':                       return 'E400114';
                case 'Facebook logging error.':                             return 'E400115';
                case 'We can\'t find a user with that e-mail address.':     return 'E400116';
                case 'The token field is required.':                        return 'E400117';
                case 'This password reset token is invalid.':               return 'E400118';
                case 'The avatar field is required.':                       return 'E400119';
                case 'The avatar must be an image.':                        return 'E400120';
                case 'The avatar may not be greater than 1024 kilobytes.':  return 'E400121';
                case '尚無存取權限。':                                         return 'E400122';
                case 'The product is invalid':                              return 'E400123';
                case '需至少有一項產品。':                                       return 'E400124';
                case 'The selected products is invalid.':                   return 'E400123';
                case 'Collection product can\'t add to customize lab':      return 'E400126';
                case 'The selected laboratory is invalid.':                 return 'E400127';
                case 'The selected product is invalid.':                    return 'E400123';
                case '無法個別刪除。':                                         return 'E400129';
                case 'laboratories invalid':                                return 'E400130';
                case 'The stock code field is required.':                   return 'E400131';
                case 'The stock code has already been taken.':              return 'E400132';
                case 'The stock code not exists.':                          return 'E400133';
                case 'No product to check order':                           return 'E400134';
                case 'product plan is not exists':                          return 'E400135';
                case 'The payment type field is required.':                 return 'E400136';
                case 'The selected payment type is invalid.':               return 'E400137';
                case 'The selected use invoice is invalid.':                return 'E400138';
                case 'The selected invoice type is invalid.':               return 'E400139';
                case 'plans error':                                         return 'E400140';
                case 'order.delete_error':                                  return 'E400141';
                case 'The promocode is invalid':                            return 'E400142';
                case 'The name field is required.':                         return 'E400143';
                case 'Invalid LoveCode.':                                   return 'E400144';
                case 'The product is uninstalled':                          return 'E400145';
                case 'No product is match':                                 return 'E400146';
                case 'The product is not exists':                           return 'E400147';
                case 'The phone has already been taken.':                   return 'E400148';
            }
        }
    }
}