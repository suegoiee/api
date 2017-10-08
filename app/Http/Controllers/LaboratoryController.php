<?php

namespace App\Http\Controllers;
use App\Repositories\LaboratoryRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LaboratoryController extends Controller
{	

    protected $laboratoryRepository;

    public function __construct(LaboratoryRepository $laboratoryRepository)
    {
	   $this->laboratoryRepository = $laboratoryRepository;
    }

    public function index(Request $request)
    {
        $user = $request->user();
        $laboratories = $user->laboratories()->with('products','products.collections')->get();
        foreach ($laboratories as $laboratory) {
            $laboratory->products->makeHidden(['status', 'users', 'info_short', 'info_more', 'price', 'expiration', 'created_at', 'updated_at', 'deleted_at', 'avatar_small', 'avatar_detail']);
            foreach ($laboratory->products as $product) {
                $product->installed = $product->users->first()->pivot->installed;
                $product->deadline = $product->users->first()->pivot->deadline;
                foreach ( $product->collections as $collection){
                    $collection->makeHidden(['avatar_small','avatar_detail']);
                }
            }
        }
        return $this->successResponse($laboratories);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $user = $request->user();
        $validator = $this->laboratoryValidator($request->all(), $user->id);
        if($validator->fails()){
            return $this->validateErrorResponse($validator->errors()->all());
        }

        $request_data = $request->only(['title','layout']);

        $laboratory = $user->laboratories()->create($request_data);

        $products = $request->input('products');
        $laboratory->products()->syncWithoutDetaching($products);

        $products_install = [];
        $products = is_array($products) ? $products:[$products];
        foreach ($products as $key => $product) {
            $user->products()->updateExistingPivot($product,['installed'=>1]);
        }

        return $this->successResponse($laboratory?$laboratory:[]);
    }

    public function show(Request $request, $id)
    {
        $user = $request->user();
        if(!($this->laboratoryRepository->isOwner($user->id,$id))){
            return $this->failedResponse(['message'=>[trans('auth.permission_denied')]]);
        }

        $laboratory = $user->laboratories()->with('products','products.collections')->find($id);
        $laboratory->products->makeHidden(['status', 'users', 'info_short', 'info_more', 'price', 'expiration', 'created_at', 'updated_at', 'deleted_at', 'avatar_small', 'avatar_detail']);
        foreach ($laboratory->products as $product) {
            $product->installed = $product->users->first()->pivot->installed;
            $product->deadline = $product->users->first()->pivot->deadline;
            foreach ( $product->collections as $collection){
                $collection->makeHidden(['avatar_small','avatar_detail']);
            }
         }

        return $this->successResponse($laboratory?$laboratory:[]);
    }

    public function edit($id)
    {
      
    }

    public function update(Request $request, $id)
    {
        $user = $request->user();
        if(!($this->laboratoryRepository->isOwner($user->id,$id))){
            return $this->failedResponse(['message'=>[trans('auth.permission_denied')]]);
        }

        $validator = $this->laboratoryValidator($request->all(), $user->id);
        if($validator->fails()){
            return $this->validateErrorResponse($validator->errors()->all());
        }

        $request_data = $request->only(['title','layout']);

        $data = array_filter($request_data, function($item){return $item!=null;});

        $user->laboratories()->where('id',$id)->update($data);
        $laboratory = $user->laboratories()->find($id);

        $products = $request->input('products');
        
        $laboratory->products()->syncWithoutDetaching($products);
        
        $products_install = [];
        $products = is_array($products) ? $products:[$products];
        foreach ($products as $key => $product) {
            $user->products()->updateExistingPivot($product,['installed'=>1]);
        }

        return $this->successResponse($laboratory?$laboratory:[]);
    }

    public function removeProducts(Request $request, $id)
    {
        $user = $request->user();
        if(!($this->laboratoryRepository->isOwner($user->id,$id))){
            return $this->failedResponse(['message'=>[trans('auth.permission_denied')]]);
        }
        $validator = $this->laboratoryValidator($request->all(), $user->id);
        if($validator->fails()){
            return $this->validateErrorResponse($validator->errors()->all());
        }
        $products = $request->input('products');
        $is_collection = $user->laboratories()->find($id)->products()->where('type','collection')->count();
        if($is_collection){
            return $this->failedResponse(['message'=>[trans('product.collection_cant_del')]]);
        }
        $user->laboratories()->find($id)->products()->detach($products);

        return $this->successResponse(['products'=>$products]);

    }

    public function destroy(Request $request, $id)
    {
        $user = $request->user();
        if(!($this->laboratoryRepository->isOwner($user->id,$id))){
            return $this->failedResponse(['message'=>[trans('auth.permission_denied')]]);
        }
        $laboratory = $user->laboratories()->find($id);
        $user->laboratories()->where('id',$id)->delete();
        $products = $laboratory->products;
        foreach ($products as $key => $product) {
            $install_num = $product->laboratories()->where('user_id',$user->id)->whereNull('deleted_at')->count();
            if($install_num==0){
                $user->products()->updateExistingPivot($product->id, ['installed'=>0]);
            }
        }
        return $this->successResponse(['id'=>$id]);

    }

    protected function laboratoryValidator(array $data , $user_id)
    {
        return Validator::make($data, [
            'title' => 'max:255',
            'layout' => 'string',
            'products' => 'exists:product_user,product_id,user_id,'.$user_id.',deleted_at,NULL',
            'products.*' => 'exists:product_user,product_id,user_id,'.$user_id.',deleted_at,NULL',
        ]);        
    }
}
