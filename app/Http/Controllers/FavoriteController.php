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
        
        $validator = $this->favoriteValidator($request->all());
        if($validator->fails()){
            return $this->validateErrorResponse($validator->errors()->all());
        }

        $request_data = $request->input('stock_id',[]);

        $user->favorites()->syncWithoutDetaching($request_data);

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
        $request_data = $request->input('stock_id',[]);

        $user->favorites()->detach($request_data);

        return $this->successResponse(['id'=>$request_data]);

    }

    protected function favoriteValidator(array $data)
    {
        return Validator::make($data, [
            'stock_id' => 'required|exists:stocks,id',
        ]);        
    }
}
