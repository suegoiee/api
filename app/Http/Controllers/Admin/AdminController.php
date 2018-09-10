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
        if(!$this->getAccessToken($request)){
            $request->session()->put('access_token', $this->clientCredentialsGrantToken());
        }
        $this->token = $request->session()->get('access_token');
    }
    protected function checkLogin($request){
        if(!$this->getAccessToken($request)){
            $request->session()->put('access_token', $this->clientCredentialsGrantToken());
        }
        $this->token = $request->session()->get('access_token');
    }
    protected function getAccessToken(Request $request){
        $http = new \GuzzleHttp\Client;
        $response = $http->request('get',url('/auth/login'),[
                'headers'=>[
                    'Accept' => 'application/json',
                    'Authorization' => 'Bearer '.isset($this->token['access_token'])? $this->token['access_token']:'',
                ],
                'form_params' => $request->all(),
            ]);

        $response_data = json_decode((string) $response->getBody(), true);
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
