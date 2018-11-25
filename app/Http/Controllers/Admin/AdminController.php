<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Traits\OauthToken;
class AdminController extends Controller
{	
    use OauthToken;
    protected $moduleName;
    protected $moduleRepository;
    protected $token;
    public function __construct(Request $request)
    {
       //$this->token = $this->clientCredentialsGrantToken();
        $that = $this;
        $this->middleware(function ($request, $next) use ($that){
            if(!$this->getAccessToken($request)){
                $session()->put('access_token', $that->clientCredentialsGrantToken($request));
            }
            $that->token = $request->session()->get('access_token');
            return $next($request);
        });
    }
    protected function checkLogin($request){
        if(!$this->getAccessToken($request)){
            $request->session()->put('access_token', $this->clientCredentialsGrantToken());
        }
        $this->token = $request->session()->get('access_token');
    }
    protected function getAccessToken(Request $request){/*
        $http = new \GuzzleHttp\Client;
        $response = $http->request('get',url('/auth/login'),[
                'headers'=>[
                    'Accept' => 'application/json',
                    'Authorization' => 'Bearer '.isset($this->token['access_token'])? $this->token['access_token']:'',
                ],
                'form_params' => $request->all(),
            ]);*/
        $request->request->add($request->all());
        $request->header->add([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer '.isset($this->token['access_token'])? $this->token['access_token']:'',
        ]);
        $tokenRequest = $request->create(
            env('APP_URL').'/auth/login',
            'get'
        );
        $instance = Route::dispatch($tokenRequest);

        $response_data = json_decode($instance->getContent(), true);
        return $response_data['status']=='success';
    }

    public function store(Request $request)
    {
        $http = new \GuzzleHttp\Client;
        $response = $http->request('post',url('/'.str_plural($this->moduleName)),[
                'headers'=>[
                    'Accept' => 'application/json',
                    'Authorization' => 'Bearer '.$this->token['access_token'],
                ],
                'form_params' => $request->all(),
            ]);

        $response_data = json_decode((string) $response->getBody(), true);
        return $this->adminResponse($request,$response_data);
    }

    public function update(Request $request, $id)
    {
        $http = new \GuzzleHttp\Client;
        $response = $http->request('put',url('/'.str_plural($this->moduleName).'/'.$id),[
                'headers'=>[
                    'Accept' => 'application/json',
                    'Authorization' => 'Bearer '.$this->token['access_token'],
                ],
                'form_params' => $request->all(),
            ]);

        $response_data = json_decode((string) $response->getBody(), true);

        return $this->adminResponse($request,$response_data);
    }

    public function destroy(Request $request, $id = 0)
    {
        $ids = $id ? [$id]:$request->input('id',[]);
        foreach ($ids as $key => $value) {
            $this->moduleRepository->delete($value);
        }
        return $id ? redirect(url('/admin/'.str_plural($this->moduleName))):$this->successResponse(['id'=>$ids]);
    }
}
