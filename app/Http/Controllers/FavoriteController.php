<?php

namespace App\Http\Controllers;

use App\Repositories\StockRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FavoriteController extends Controller
{	

    protected $stockRepository;
    public function __construct(StockRepository $stockRepository)
    {
        $this->stockRepository = $stockRepository;
    }

    public function index(Request $request)
    {
        $favorites = $request->user()->favorites;
        foreach ($favorites as $key => $favorite) {
            $favorite->stock_name = $favorite->company ? $favorite->company->stock_name:'';
            $favorite->id = $key+1;
            //$favorite->sort= $key+1;
        }

        return $this->successResponse($favorites?$favorites->makeHidden(['company']):[]);
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

        $stock_code = $request->input('stock_code');
        
        $stock = $this->stockRepository->getBy(['stock_code'=>$stock_code]);
        if(!$stock){
            return $this->failedResponse(['message'=>'The stock code not exists.']);
        }

        $favorite = $user->favorites()->where('stock_code', $stock_code)->first();

        if(!$favorite){
            $stock = $user->favorites()->create(['stock_code'=>$stock_code, 'stock_name'=>$stock->stock_name]);
            return $this->successResponse(['stock_code'=>$stock_code, 'stock_name'=>$stock->stock_name]);
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

    public function destroy(Request $request, $stock_code=0)
    {
        $stock_code = $stock_code != 0 ? $stock_code : $request->input('stock_code');
        $user = $request->user();

        $favorite = $user->favorites()->where('stock_code', $stock_code)->first();
        
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
            //'stock_name' => 'required',
        ]);        
    }
}
