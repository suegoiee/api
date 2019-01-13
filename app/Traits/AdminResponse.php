<?php 
namespace App\Traits;
trait AdminResponse
{
    protected function adminResponse($request, $response_data){
    	if($response_data['status']=='error'){
    		return redirect()->back()->with('errors',$response_data['error']['message']);
        }
        return $request->input('action')=="save_exit" ? redirect(url('/admin/'.str_plural($this->moduleName))) : redirect(url('/admin/'.str_plural($this->moduleName).'/'.$response_data['data']['id'].'/edit'));
    }
}