<?php

namespace App\Http\Controllers;

use App\Repositories\ProductRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserProductController extends Controller
{	
    protected $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
	   $this->productRepository = $productRepository;
    }

    public function index(Request $request)
    {
        $products = $request->user()->products;
        foreach ($products as $key => $product) {
            $product->title = $product->pivot->title;
            $product->deadline = $product->pivot->deadline;
        }
        
        return $this->successResponse($products?$products:[]);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $user = $request->user();
        $validator = $this->productValidator($request->all());
        if($validator->fails()){
            return $this->validateErrorResponse($validator->errors()->all());
        }

        $products = $request->input('products',[]);

        $user->products()->syncWithoutDetaching($products);
        foreach ($products as $key => $product) {
            $product_data = $this->productRepository->get($product);
            if($product_data->type=='collection'){
                $collections_id = $product_data->collections->map(function($item,$key){return $item->id;});
                $user->products()->syncWithoutDetaching($collections_id);
            }
        }

        return $this->successResponse($products?$products:[]);
    }

    public function show(Request $request, $id)
    {
        
        $product = $request->user()->products()->with($id,['tags','collections','avatar_small','avatar_detail'])->find($id);
        $product->title= $product->pivot->title;
        $product->deadline= $product->pivot->deadline;

        return $this->successResponse($product?$product:[]);
    }

    public function edit($id)
    {
      
    }

    public function update(Request $request, $id)
    {
        $validator = $this->productValidator($request->all());
        if($validator->fails()){
            return $this->validateErrorResponse($validator->errors()->all());
        }

        $request_data = $request->only(['deadline','title']);
        $data = array_filter($request_data, function($item){return $item!=null;});
        $request->user()->products()->updateExistingPivot($id, $data);

        return $this->successResponse($request_data?$request_data:[]);
    }

    public function destroy(Request $request, $id)
    {
        $request->user()->products()->detach($id);
        return $this->successResponse(['id'=>$id]);

    }

    protected function productValidator(array $data)
    {
        return Validator::make($data, [
            'products.*' => 'exists:products,id',
            'deadline'=>'date',
        ]);        
    }
}
