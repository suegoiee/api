<?php

namespace App\Http\Controllers\Analyst;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Traits\OauthToken;
class AnalystController extends Controller
{	
    use OauthToken;
    protected $moduleName;
    protected $moduleRepository;
    protected $token;
    public function analystResponse($request, $data)
    {
        return $request->input('action')=="save_exit" ? redirect(url('/analyst/'.str_plural($this->moduleName))) : redirect(url('/analyst/'.str_plural($this->moduleName).'/'.$data['id'].'/edit'));
    }
    public function analystFailResponse($request)
    {
        return redirect()->back()
            ->withInput($request->all())
            ->withErrors();
        return $request->input('action')=="save_exit" ? redirect(url('/admin/'.str_plural($this->moduleName))) : redirect(url('/admin/'.str_plural($this->moduleName).'/'.$response_data['data']['id'].'/edit'));
    }
    public function __construct(Request $request)
    {
       
    }
}
