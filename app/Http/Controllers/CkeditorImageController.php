<?php

namespace App\Http\Controllers;

use Route;
use App\Traits\ImageStorage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CkeditorImageController extends Controller
{	
    use ImageStorage;
    public function __construct()
    {
	   
    }

    public function index()
    {
        //
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $path = $this->storeImage($request->file('upload'), $request->input('CKEditor'));
        $hashName = pathinfo($path)['basename'];
        $url = url('storage/'.$path);
        return response()->json(['uploaded'=>1,'fileName'=>$hashName,'url'=>$url]);
        //return '<script>window.parent.CKEDITOR.tools.callFunction('.$request->input('CKEditorFuncNum').', "'.$url.'");window.close();</script>';
    }

    public function show(Request $request)
    {
        
    }

    public function edit($id)
    {
      
    }

    public function update(Request $request)
    {
      
    }

    public function destroy(Request $request)
    {
       
    }

    protected function avatarValidator(array $data)
    {
        return Validator::make($data, [
            'upload' => 'required|image|max:2048',
        ]);
    }
}
