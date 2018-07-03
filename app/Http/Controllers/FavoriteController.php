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

        $favorite = $user->favorites()->where('stock_code', $request_data['stock_code'])->first();
        
        if(!$favorite){
            $user->favorites()->create($request_data);
            return $this->successResponse($request_data);
        }else{
            return $this->failedResponse(['message'=>'The stock code has already been taken.']);
        }
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

    public function destroy(Request $request, $id)
    {
        $user = $request->user();

        $favorite = $user->favorites()->where('id', $id)->first();
        
        if($favorite){
            $favorite->delete();
            return $this->successResponse($favorite);
        }else{
            return $this->failedResponse(['message'=>'The stock code not exists.']);
        }
    }

    protected function favoriteValidator(array $data, $user_id)
    {
        return Validator::make($data, [
            'stock_code' => 'required|unique:favorites,stock_code,NULL,id,user_id,'.$user_id,
            'stock_name' => 'required',
        ]);        
    }
}
