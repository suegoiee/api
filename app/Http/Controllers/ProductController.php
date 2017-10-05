<?php

namespace App\Http\Controllers;

use App\Repositories\ProductRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{	
    protected $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
	   $this->productRepository = $productRepository;
    }

    public function index()
    {
        $product = $this->productRepository->getsWith(['tags','collections']);

        return $this->successResponse($product?$product:[]);
    }
    public function onShelves()
    {
        $product = $this->productRepository->getsWithByStatus(['tags','collections']);

        return $this->successResponse($product?$product:[]);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $validator = $this->productValidator($request->all());
        if($validator->fails()){
            return $this->validateErrorResponse($validator->errors()->all());
        }

        $request_data = $request->only(['name','model','info_short','info_more','type','price','expiration','status','faq']);
        $request_data['expiration'] = $request_data['expiration']? $request_data['expiration']:0;
        $product = $this->productRepository->create($request_data);

        $tags = $request->input('tags',[]);
        $product->tags()->attach($tags);

        if( $product->type =='collection' ){
            $collections = $request->input('collections',[]);
            $product->collections()->attach($collections);
        }
        /*
        $avatar_small = $request->file('avatar_small');
        if($avatar_small){
            $avatar_small_data = [
                'path' => $this->storeAvatar($product->id,$request->file('avatar_small'),'product'),
                'type' => 'small'
            ];
            $product->avatar()->create($avatar_small);
        }

        $avatar_detail = $request->file('avatar_detail');
        if($avatar_detail){
            foreach ($avatar_detail as $key => $avatar) {
                $avatar_data = [
                    'path' => $this->storeAvatar($product->id,$request->file('avatar_small'),'product'),
                    'type' => 'detail'
                ];
                $product->avatar()->create($avatar_data);
            }
        }*/
        return $this->successResponse($product?$product:[]);
    }

    public function show(Request $request, $id)
    {
        
        $product = $this->productRepository->getWith($id,['tags','collections']);

        return $this->successResponse($product?$product:[]);
    }
    public function onShelf(Request $request, $id)
    {
        
        $product = $this->productRepository->getWithByStatus($id, ['tags','collections']);

        return $this->successResponse($product?$product:[]);
    }

    public function edit($id)
    {
      
    }

    public function update(Request $request, $id)
    {
        $validator = $this->productUpdateValidator($request->all());
        if($validator->fails()){
            return $this->validateErrorResponse($validator->errors()->all());
        }

        $request_data = $request->only(['name','model','info_short','info_more','type','price','expiration','status','faq']);
        $data = array_filter($request_data, function($item){return $item!=null;});

        $product = $this->productRepository->update($id,$data);

        $tags = $request->input('tags',[]);
        $product->tags()->sync($tags);

        if( $product->type =='collection' ){
            $collections = $request->input('collections',[]);
            $product->collections()->sync($collections);
        }

        return $this->successResponse($product?$product:[]);
    }

    public function destroy(Request $request, $id)
    {

        $this->productRepository->delete($id);
        return $this->successResponse(['id'=>$id]);

    }

    protected function productValidator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'model' => 'max:255',
            'api' => 'max:255',
            'info_short'=>'required|max:255',
            'info_more' => 'string',
            'type'=>'required|max:255',
            'price'=>'required|numeric',
            'faq'=>'string',
        ]);        
    }

    protected function productUpdateValidator(array $data)
    {
        return Validator::make($data, [
            'name' => 'max:255',
            'model' => 'max:255',
            'info_short'=>'max:255',
            'info_more'=>'string',
            'type'=>'max:255',
            'price'=>'numeric',
            'faq'=>'string',
        ]);        
    }
}
