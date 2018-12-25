<?php

namespace App\Http\Controllers;
use Storage;

use Illuminate\Http\File;
use App\Traits\ImageStorage;
use App\Repositories\LaboratoryRepository;
use App\Repositories\ProductRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LaboratoryController extends Controller
{	
    use ImageStorage;
    protected $laboratoryRepository;
    protected $productRepository;

    public function __construct(LaboratoryRepository $laboratoryRepository, ProductRepository $productRepository)
    {
	   $this->laboratoryRepository = $laboratoryRepository;
       $this->productRepository = $productRepository;
    }

    public function index(Request $request)
    {
        $user = $request->user();
        $laboratories = $user->laboratories()->orderBy('sort')->get()->makeHidden(['collection_product_id']);

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

        $request_data = $request->only(['title','layout','sort']);
        $request_data['customized']=1;
        $request_data['sort'] = isset($request_data['sort']) ? $request_data['sort']:0;
        $laboratory = $user->laboratories()->create($request_data);

        $products = $request->input('products',[]);
        $products_data = [];
        foreach ($products as $key => $value) {
            $products_data[$value]=['sort'=>$key];
        }
        $laboratory->products()->syncWithoutDetaching($products_data);
        $products_install = [];

        $products = is_array($products) ? $products:[$products];
        foreach ($products as $key => $product) {
            $product_data = $user->products()->find($product);
            if($key==0 && $product_data->type=='collection'){
                if($product_data->avatar_small){
                    $this->create_avatar($laboratory, $product_data->avatar_small);
                }
            }
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

        $laboratory = $user->laboratories()->with(['products','products.collections','products.faqs'])->find($id);
        $laboratory->products->makeHidden(['status', 'users', 'info_short', 'info_more', 'price', 'expiration', 'created_at', 'updated_at', 'deleted_at', 'avatar_small', 'avatar_detail']);

        if(!$laboratory->customized){
            $collect_product = $user->products()->find($laboratory->collection_product_id);
            $deadline = $collect_product && $collect_product->pivot->deadline ? $collect_product->pivot->deadline : 0;
        }
        foreach ($laboratory->products as $product) {
            $product_user = $product->users()->find($user->id);
            if(!$laboratory->customized){
                $product->installed = 1;
                $product->deadline = $deadline ? $deadline : 0;
            }else{
                $product->installed = $product_user ? $product_user->pivot->installed : 0;
                $product->deadline = $product_user ? $product_user->pivot->deadline : 0;
            }
            $product->sort = $product->pivot->sort;
            foreach ( $product->collections as $collection){
                $collection->makeHidden(['avatar_small','avatar_detail']);
            }
         }
         $laboratory->products=$laboratory->products->sortBy('sort');
        
        return $this->successResponse($laboratory?$laboratory->makeHidden(['collection_product_id']):[]);
    }

    public function mapping(Request $request, $pathname)
    {
        $user = $request->user();
        $product = $user->products()->where('pathname', $pathname)->first();
        if(!$product){
            return $this->failedResponse(['message'=>[trans('product.no_product_is_match')]]);
        }
        $laboratory = $user->laboratories()->with(['products','products.collections','products.faqs'])->where('collection_product_id', $product->id)->first();
        if(!$laboratory){
            return $this->failedResponse(['message'=>[trans('laboratory.product_is_uninstalled')]]);        
        }
        $laboratory->products->makeHidden(['status', 'users', 'info_short', 'info_more', 'price', 'expiration', 'created_at', 'updated_at', 'deleted_at', 'avatar_small', 'avatar_detail']);

        if(!$laboratory->customized){
            $collect_product = $user->products()->find($laboratory->collection_product_id);
            $deadline = $collect_product && $collect_product->pivot->deadline ? $collect_product->pivot->deadline : 0;
        }
        foreach ($laboratory->products as $product) {
            $product_user = $product->users()->find($user->id);
            if(!$laboratory->customized){
                $product->installed = 1;
                $product->deadline = $deadline ? $deadline : 0;
            }else{
                $product->installed = $product_user ? $product_user->pivot->installed : 0;
                $product->deadline = $product_user ? $product_user->pivot->deadline : 0;
            }
            $product->sort = $product->pivot->sort;
            foreach ( $product->collections as $collection){
                $collection->makeHidden(['avatar_small','avatar_detail']);
            }
         }
         $laboratory->products=$laboratory->products->sortBy('sort');
        return $this->successResponse($laboratory?$laboratory->makeHidden(['collection_product_id']):[]);
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

        $request_data = $request->only(['title','layout', 'sort']);

        $data = array_filter($request_data, function($item){return $item!=null;});

        $user->laboratories()->where('id',$id)->update($data);

        $laboratory = $user->laboratories()->find($id);

		if($laboratory->customized == 0){
            return $this->failedResponse(['message'=>[trans('product.collection_cant_del')]]);
        }
        $products = $request->input('products');


        $products_install = $products ? (is_array($products) ? $products:[$products]) : [];
        foreach ($products_install as $key => $product_install) {
            $product_install_data = $this->productRepository->get($product_install);
            if($product_install_data && $product_install_data->type=='collection'){
                return $this->failedResponse(['message'=>['Collection product can\'t add to customize lab']]);
            }
        }
        if(count($products_install)==0){
        	return $this->failedResponse(['message'=>[trans('product.cant_no_products')]]);
        }
        $products_remove = $laboratory->products()->whereNotIn('id',$products_install)->get();

        $products_data = [];
        foreach ($products_install as $key => $value) {
            $products_data[$value]=['sort'=>$key];
        }
        $laboratory->products()->sync($products_data);

        foreach ($products_install as $key => $product) {
            $user->products()->updateExistingPivot($product,['installed'=>1]);
        }
        foreach ($products_remove as $key => $product) {
        	$install_num = $product->laboratories()->where('user_id',$user->id)->whereNull('deleted_at')->count();
	        if($install_num==0){
	            $user->products()->updateExistingPivot($product->id, ['installed'=>0]);
	        }
        }

        return $this->successResponse($laboratory?$laboratory->makeHidden(['collection_product_id']):[]);
    }

    public function removeProducts(Request $request, $id)
    {
        $user = $request->user();
        if(!($this->laboratoryRepository->isOwner($user->id,$id))){
            return $this->failedResponse(['message'=>[trans('auth.permission_denied')]]);
        }
        
        $products = $request->input('products');


        $laboratory = $user->laboratories()->find($id);
        if($laboratory->customized == 0){
            return $this->failedResponse(['message'=>[trans('product.collection_cant_del')]]);
        }
        $products_remove = $products ? (is_array($products) ? $products:[$products]) : [];
        $laboratory->products()->detach($products_remove);

        foreach ($products_remove as $key => $product) {
        	$product_data = $user->products()->find($product);
        	if($product_data){
	            $install_num = $product_data->laboratories()->where('user_id',$user->id)->whereNull('deleted_at')->count();
	            if($install_num==0){
	                $user->products()->updateExistingPivot($product_data->id, ['installed'=>0]);
	            }
        	}else{
                return $this->failedResponse(['message'=>['The selected products is invalid.']]);
            }
        }

        return $this->successResponse(['products'=>$products_remove]);

    }

    public function destroy(Request $request, $id)
    {
        $user = $request->user();
        if(!($this->laboratoryRepository->isOwner($user->id,$id))){
            return $this->failedResponse(['message'=>[trans('auth.permission_denied')]]);
        }
        $laboratory = $user->laboratories()->find($id);
        $user->laboratories()->where('id',$id)->delete();
        
        if($laboratory->customized){
            $products = $laboratory->products;
            foreach ($products as $key => $product) {
                $install_num = $product->laboratories()->where('user_id',$user->id)->whereNull('deleted_at')->count();
                if($install_num==0){
                    $user->products()->updateExistingPivot($product->id, ['installed'=>0]);
                }
            }
        }else{
            $user->products()->updateExistingPivot($laboratory->collection_product_id, ['installed'=>0]);
        }

        return $this->successResponse(['message'=>['The laboratory was deleted.'], 'deleted'=>1]);

    }
    public function sorted(Request $request)
    {
        $user = $request->user();
        $sorted_laboratories = $request->input('sorted_laboratories', []);
        foreach ($sorted_laboratories as $key => $laboratory) {
            if($user->laboratories()->where('id', $laboratory)->count()==0){
                return $this->failedResponse(['message'=>['The selected laboratory is invalid.']]);
            }
            $user->laboratories()->where('id', $laboratory)->update(['sort' => $key]);
        }

        return $this->successResponse(['sorted_laboratories' => $sorted_laboratories]);
    }
    public function productSorted(Request $request, $id)
    {
        $user = $request->user();
        if(!($this->laboratoryRepository->isOwner($user->id,$id))){
            return $this->failedResponse(['message'=>[trans('auth.permission_denied')]]);
        }
        
        $laboratory = $user->laboratories()->find($id);
        $sorted_products = $request->input('sorted_products', []);
        foreach ($sorted_products as $key => $product) {
            if($laboratory->products()->where('id', $product)->count()==0){
                return $this->failedResponse(['message'=>['The selected product is invalid.']]);
            }
            $laboratory->products()->updateExistingPivot($product, ['sort'=>$key]);
        }

        return $this->successResponse(['sorted_products'=>$sorted_products]);
    }

    protected function laboratoryValidator(array $data , $user_id)
    {
        return Validator::make($data, [
            'title' => 'max:255',
            'layout' => 'string',
            'sort'=>'numeric',
            'products' => 'exists:product_user,product_id,user_id,'.$user_id.',deleted_at,NULL|nullable',
            'products.*' => 'exists:product_user,product_id,user_id,'.$user_id.',deleted_at,NULL',
        ]);        
    }

    private function create_avatar($laboratory, $avatar){
        if(!$avatar){
            return false;
        }
        $contents = new File(storage_path('app/public/'.$avatar->path));
        $path = $this->createAvatar($contents, $laboratory->id, 'laboratories');
        $data = ['path' => $path,'type'=>'normal'];
        return $laboratory->avatars()->create($data);
    }
}
