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

        $request_data = $request->only(['stock_code','stock_name']);

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
        $stock_code = $request->input('stock_code');

        $user->favorites()->where('stock_code',$stock_code)->delete();

        return $this->successResponse(['stock_code'=>$stock_code]);

    }

    protected function favoriteValidator(array $data, $user_id)
    {
        return Validator::make($data, [
            'stock_code' => 'required|unique:favorites,stock_code,NULL,id,user_id,'.$user_id,
            'stock_name' => 'required',
        ]);        
    }
}
