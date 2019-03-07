<?php 
namespace App\Traits;
trait AdminResponse
{
    protected function adminResponse($request, $response_data){
        if($response_data['status']=='error'){
    		return redirect()->back()->with('errors',$response_data['error']['message']);
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