<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Traits\OauthToken;
use Illuminate\Support\Facades\Route;
use Laravel\Passport\PersonalAccessClient;
use Maatwebsite\Excel\Facades\Excel;
class AdminController extends Controller
{	
    use OauthToken;
    protected $moduleName;
    protected $moduleRepository;
    protected $token;
    public function __construct($request)
    {
        $client_request = clone $request;
        if($request->session()->exists('access_token') && $request->session()->get('token_expired_at') > time()){
            $check_request = clone $request;
            if($this->checkLogin($check_request, session('access_token'))){
                $this->token = ['access_token'=>session('access_token')];
            }else{
                $this->token = $this->clientCredentialsGrantToken($client_request);
                $request->session()->put('access_token', $this->token['access_token']);
                $request->session()->put('token_expired_at', time() + $this->token['expires_in']);
            }
        }else{
            $this->token = $this->clientCredentialsGrantToken($client_request);
            if(isset($this->token['access_token'])){
                $request->session()->put('access_token', $this->token['access_token']);
                $request->session()->put('token_expired_at', time() + $this->token['expires_in']);
            }
        }
    }
    protected function checkLogin($request, $access_token){
        $request->headers->set('Accept','application/json');
        $request->headers->set('Authorization','Bearer '.$access_token);
        $tokenRequest = $request->create(
            env('APP_URL').'/client/token/check',
            'get'
        );
        $instance = Route::dispatch($tokenRequest);

        $response_data = json_decode($instance->getContent(), true);
        return $response_data['status']=='success';
    }
    protected function getAccessToken($request){
        $request->request->add($request->all());
        $request->headers->set('Accept','application/json');
        $request->headers->set('Authorization','Bearer '.isset($this->token['access_token'])? $this->token['access_token']:'');
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
        $tokenRequest = $request->create(
            env('APP_URL').'/'.str_plural($this->moduleName),
            'post'
        );
        
        $tokenRequest->request->add($request->all());
        $tokenRequest->headers->set('Accept','application/json');
        $tokenRequest->headers->set('Authorization','Bearer '.isset($this->token['access_token'])? $this->token['access_token']:'');
        $instance = Route::dispatch($tokenRequest);

        $response_data = json_decode($instance->getContent(), true);
        return $this->adminResponse($request, $response_data);
    }

    public function update(Request $request, $id)
    {
        $tokenRequest = $request->create(
            url('/'.str_plural($this->moduleName).'/'.$id),
            'put'
        );
        $tokenRequest->request->add($request->all());
        $tokenRequest->headers->set('Accept','application/json');
        $tokenRequest->headers->set('Authorization','Bearer '.isset($this->token['access_token'])? $this->token['access_token']:'');
        $instance = Route::dispatch($tokenRequest);
        $response_data = json_decode($instance->getContent(), true);
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

    public function tableExport($data)
    {
        $filename = time();
        Excel::create($filename, function($excel) use ($data) {
            $excel->sheet('Sheetname', function($sheet) use ($data) {
                $sheet->fromArray($data);
            });
        })->download('xls');
    }
}
