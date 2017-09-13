<?php 
namespace App\Traits;

trait AdminResponse
{
    protected function adminResponse($request, $response_data){
        if($response_data['status']=='error'){
            return redirect()->back()
            ->withInput($request->all())
            ->withErrors($response_data['error']);
        }
        return $request->input('action')=="save_exit" ? redirect(url('/admin/'.str_plural($this->moduleName))) : redirect(url('/admin/'.str_plural($this->moduleName).'/'.$response_data['data']['id'].'/edit'));
    }
}