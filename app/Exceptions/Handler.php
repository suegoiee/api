<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\AuthenticationException;
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
            return $logger->error((string) $exception->getResponse()->getBody());
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
            		'error' => ['title'=> 'Sorry, something went wrong.']
        	];

        	// If the app is in debug mode
        	/*if (config('app.debug')) {
           	 	// Add the exception class name, message and stack trace to response
            	//$response['error']['exception'] = get_class($exception); // Reflection might be better here
                if ($exception instanceof \GuzzleHttp\Exception\RequestException) {
                    $exception_response = json_decode($exception->getResponse()->getBody(),true);
                    $response['error']['type'] = $exception_response['error'];
                    $response['error']['message'] = [$exception_response['message']];
                    //$response['error']['trace'] = $exception->getTrace();
                }else if($exception instanceof AuthenticationException){
                    $response['error']['message'] = ['Unauthenticated.'];
                }
        	}*/
            if ($exception instanceof \GuzzleHttp\Exception\RequestException) {
                $message_body = $exception->getResponse()->getBody();
                $exception_response = json_decode($message_body,true);
                $response['error']['type'] = $exception_response['error'];
                $response['error']['message'] = [(isset($exception_response['message']) ? $exception_response['message']:$message_body)];
                //$response['error']['trace'] = $exception->getTrace();
            }else if($exception instanceof AuthenticationException){
                $response['error']['message'] = ['Unauthenticated.'];
            }else if($exception instanceof \Laravel\Passport\Exceptions\MissingScopeException){
                $response['error']['message'] = ['Permission denied.'];
            }else{
                $response['error']['exception'] = get_class($exception);
                $response['error']['message'] = [$exception->getMessage()];
            }

        	// Default response of 400
        	$status = 400;

        	// If this exception is an instance of HttpException
        	if ($this->isHttpException($exception)) {
            	// Grab the HTTP status code from the Exception
            	$status = $exception->getStatusCode();
        	}

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
        if ($request->wantsJson()){//->expectsJson()) {
            return response()->json(['error' => ['message'=>['Unauthenticated.']]], 401);
        }

        return redirect()->guest(route('login'));
    }
}
