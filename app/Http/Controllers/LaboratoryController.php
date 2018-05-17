<?php

namespace App\Http\Controllers;
use Storage;

use Illuminate\Http\File;
use App\Traits\ImageStorage;
use App\Repositories\LaboratoryRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LaboratoryController extends Controller
{	
    use ImageStorage;
    protected $laboratoryRepository;

    public function __construct(LaboratoryRepository $laboratoryRepository)
    {
	   $this->laboratoryRepository = $laboratoryRepository;
    }

    public function index(Request $request)
    {
        $user = $request->user();
        $laboratories = $user->laboratories()->with(['products','products.collections','products.faqs'])->orderBy('sort')->get()->makeHidden(['collection_product_id']);
        foreach ($laboratories as $laboratory) {
            $laboratory->products->makeHidden(['status', 'users', 'info_short', 'info_more', 'price', 'expiration', 'created_at', 'updated_at', 'deleted_at', 'avatar_small', 'avatar_detail']);
            
            if(!$laboratory->customized){
                $collect_product = $user->products()->find($laboratory->collection_product_id);
                $deadline = $collect_product? $collect_product->pivot->deadline:0;
            }

            foreach ($laboratory->products as $product) {
                $product_user = $product->users()->find($user->id);
                if(!$laboratory->customized){
                    $product->installed = 1;
                    $product->deadline = $deadline;
                }else{
                    $product->installed = $product_user ? $product_user->pivot->installed : 0;
                    $product->deadline = $product_user ? $product_user->pivot->deadline : null;
                }
                $product->sort = $product->pivot->sort;
                foreach ( $product->collections as $collection){
                    $collection->makeHidden(['avatar_small','avatar_detail']);
                }
            }
            //$laboratory->products=$laboratory->products->sortBy('sort');
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
            $deadline = $collect_product? $collect_product->pivot->deadline:0;
        }
        foreach ($laboratory->products as $product) {
            $product_user = $product->users()->find($user->id);
            if(!$laboratory->customized){
                $product->installed = 1;
                $product->deadline = $deadline;
            }else{
                $product->installed = $product_user ? $product_user->pivot->installed : 0;
                $product->deadline = $product_user ? $product_user->pivot->deadline : null;
            }
            $product->sort = $product->pivot->sort;
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

        $request_data = $request->only(['title','layout', 'sort']);

        $data = array_filter($request_data, function($item){return $item!=null;});

        $user->laboratories()->where('id',$id)->update($data);

        $laboratory = $user->laboratories()->find($id);

		if($laboratory->customized == 0){
            return $this->failedResponse(['message'=>[trans('product.collection_cant_del')]]);
        }
        $products = $request->input('products');


        $products_install = $products ? (is_array($products) ? $products:[$products]) : [];
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

        return $this->successResponse($laboratory?$laboratory:[]);
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

        return $this->successResponse(['id'=>$id]);

    }
    public function sorted(Request $request)
    {
        $user = $request->user();
        $validator = Validator::make($request->all(), [
            'sorted_laboratories.*' => 'exists:laboratories,id,user_id,'.$user->id.',deleted_at,NULL']);   
        if($validator->fails()){
            return $this->validateErrorResponse($validator->errors()->all());
        }
        $sorted_laboratories = $request->input('sorted_laboratories', []);
        foreach ($sorted_laboratories as $key => $laboratory) {
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
        $validator = Validator::make($request->all(), [
            'sorted_products.*' => 'exists:laboratory_product,product_id,laboratory_id,'.$id
        ]);
        if($validator->fails()){
            return $this->validateErrorResponse($validator->errors()->all());
        }
        $laboratory = $user->laboratories()->find($id);
        $laboratory_products=$laboratory->products();

        $sorted_products = $request->input('sorted_products', []);
        foreach ($sorted_products as $key => $product) {
            $laboratory_products->updateExistingPivot($product, ['sort'=>$key]);
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
