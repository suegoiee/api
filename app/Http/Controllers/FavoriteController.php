<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FavoriteController extends Controller
{	

    public function __construct()
    {

    }

    public function index(Request $request)
    {
        $favorites = $request->user()->favorites;

        return $this->successResponse($favorites?$favorites:[]);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $user = $request->user();
        
        $validator = $this->favoriteValidator($request->all(), $user->id);
        if($validator->fails()){
            return $this->validateErrorResponse($validator->errors()->all());
        }

        $request_data = $request->only(['code','name']);

        $user->favorites()->create($request_data);

        return $this->successResponse($request_data);
    }

    public function show(Request $request, $id)
    {
        
    }

    public function edit($id)
    {
      
    }

    public function update(Request $request, $id)
    {

    }

    public function destroy(Request $request)
    {
        $user = $request->user();
        
        $validator = $this->favoriteValidator($request->all());
        if($validator->fails()){
            return $this->validateErrorResponse($validator->errors()->all());
        }
        $stock_code = $request->input('code');

        $user->favorites()->where('code',$stock_code)->delete();

        return $this->successResponse(['code'=>$stock_code]);

    }

    protected function favoriteValidator(array $data, $user_id)
    {
        return Validator::make($data, [
            'code' => 'required|unique:favorites,code,NULL,id,user_id,'.$user_id,
            'name' => 'required',
        ]);        
    }
}
