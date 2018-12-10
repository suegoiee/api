<?php

namespace App\Exceptions;
use Route;
use Exception;
use Illuminate\Auth\AuthenticationException;
use League\OAuth2\Server\Exception\OAuthServerException;
use League\OAuth2\Server\Exception\OAuthException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        \Illuminate\Auth\AuthenticationException::class,
        \Illuminate\Auth\Access\AuthorizationException::class,
        \Symfony\Component\HttpKernel\Exception\HttpException::class,
        \Illuminate\Database\Eloquent\ModelNotFoundException::class,
        \Illuminate\Session\TokenMismatchException::class,
        \Illuminate\Validation\ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        //parent::report($exception);
         // this is from the parent method
        try {
            $logger = $this->container->make(\Psr\Log\LoggerInterface::class);
        } catch (Exception $ex) {
            throw $exception; // throw the original exception
        }
        // this is the new custom handling of guzzle exceptions
        if ($exception instanceof \GuzzleHttp\Exception\RequestException) {
            // get the full text of the exception (including stack trace),
            // and replace the original message (possibly truncated),
            // with the full text of the entire response body.
            $message = str_replace(
                rtrim($exception->getMessage()),
                (string) $exception->getResponse()->getBody(),
                (string) $exception
            );

            // log your new custom guzzle error message
            return $logger->error($exception);
        }

        // make sure to still log non-guzzle exceptions
        $logger->error($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {	
	if ($request->wantsJson()) {
        	// Define the response
      	     $response = [
            	//'error' => ['title'=> 'Sorry, something went wrong.']
        	];

            if ($exception instanceof \GuzzleHttp\Exception\RequestException) {
                $message_response = $exception->getResponse();
                $message_body = $message_response ? $exception->getResponse()->getBody() : $message_response;

                $exception_response = json_decode($message_body,true);

                $response['error']['type'] = isset($exception_response['error']) ? $exception_response['error'] : 'error';

                $response['error']['message'] = [(isset($exception_response['message']) ? $exception_response['message']:$message_body)];
                $response['error']['code'] = "E400002";
                //$response['error']['trace'] = $exception->getTrace();
            }else if($exception instanceof AuthenticationException){
                $response['error']['message'] = ['Unauthenticated.'];
                $response['error']['code'] = "E400001";
            }else if($exception instanceof \Laravel\Passport\Exceptions\MissingScopeException){
                $response['error']['message'] = ['Permission denied.'];
                $response['error']['code'] = "E400003";
            }else if($exception instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException){
                $response['error']['message'] = ['Not found.'];
                $response['error']['code'] = "E400004";
            }else if($exception instanceof \Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException){
                $response['error']['message'] = ['Method not found.'];
                $response['error']['code'] = "E400005";
            }else if($exception instanceof \Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException){
                $response['error']['message'] = ['Method not found.'];
                $response['error']['code'] = "E400006";
            }else if($exception instanceof OAuthServerException){
                $response['error']['message'] = ['The user credentials were incorrect.'];
                $response['error']['code'] = "E400007";
            }else if($exception instanceof OAuthException){
                $response['error']['message'] = ['???'];
                $response['error']['code'] = "E400008";
            }else{
                $response['error']['exception'] = get_class($exception);
                $response['error']['message'] = [$exception->getMessage()];
                $response['error']['code'] = "E400000";
            }

        	// Default response of 400
        	$status = 200;

        	// If this exception is an instance of HttpException
        	/*if ($this->isHttpException($exception)) {
            	// Grab the HTTP status code from the Exception
            	//$status = $exception->getStatusCode();
                $response['error']['message']= 'Http error.';
        	}*/
            $uri = $request->path();
            $actionMethod = $request->method();
            $response['uri'] = $uri;
            $response['method'] = $actionMethod;
            $response['status'] = 'error';
        	// Return a JSON response with the response array and status code
        	return response()->json($response, $status);
    	}
        return parent::render($request, $exception);
    }

    /**
     * Convert an authentication exception into an unauthenticated response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return \Illuminate\Http\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        $uri = $request->path();
        $actionMethod = $request->method();
        if ($request->wantsJson()){//->expectsJson()) {
            return response()->json(['status'=>'error','error'=>['message'=>['unauthorized'], 'code'=>"E400001"], 'uri'=>$uri, 'method'=>$actionMethod], 200);
        }
        $guard = array_get($exception->guards(), 0);
        switch ($guard) {
                case 'admin':
                    $login = 'admin.login';
                    break;
                case 'analyst':
                    $login = 'analyst.login';
                    break;
                default:
                    return response()->json(['status'=>'error','error'=>['message'=>['unauthorized'], 'code'=>"E400001"], 'uri'=>$uri, 'method'=>$actionMethod], 200);
                    break;
        }
        return redirect()->guest(route($login));
    }
}
