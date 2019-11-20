<?php 
namespace App\Traits;
trait AdminResponse
{
    protected function adminResponse($request, $response_data){
        if($response_data['status']=='error'){
            $request->session()->put('errors', $response_data['error']['message'][0]);
            return back();//view('admin.form_error');
        }
        return $request->input('action')=="save_exit" ? $this->saveExitResponse($response_data): $this->saveResponse($response_data);
    }
    protected function saveExitResponse($response_data)
    {
    	return redirect(url('/admin/'.str_plural($this->moduleName)));
    }
    protected function saveResponse($response_data)
    {
    	return redirect(url('/admin/'.str_plural($this->moduleName).'/'.$response_data['data']['id'].'/edit'));
    }
}