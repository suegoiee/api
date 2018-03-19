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
        $laboratories = $user->laboratories()->with(['products','products.collections','products.faqs'])->get();
        foreach ($laboratories as $laboratory) {
            $laboratory->products->makeHidden(['status', 'users', 'info_short', 'info_more', 'price', 'expiration', 'created_at', 'updated_at', 'deleted_at', 'avatar_small', 'avatar_detail']);
            foreach ($laboratory->products as $product) {
                $product->installed = $product->users->where('id', $user->id)->first()->pivot->installed;
                $product->deadline = $product->users->where('id', $user->id)->first()->pivot->deadline;
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

        $request_data = $request->only(['title','layout','sort']);
        $request_data['customized']=1;
        $laboratory = $user->laboratories()->create($request_data);

        $products = $request->input('products',[]);
        $laboratory->products()->syncWithoutDetaching($products);
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
        foreach ($laboratory->products as $product) {
            $product->installed = $product->users->first()->pivot->installed;
            $product->deadline = $product->users->first()->pivot->deadline;
            $product->sort = $product->users->first()->pivot->sort;
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
        $laboratory->products()->sync($products_install);
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
        $products = $laboratory->products;
        foreach ($products as $key => $product) {
            $install_num = $product->laboratories()->where('user_id',$user->id)->whereNull('deleted_at')->count();
            if($install_num==0){
                $user->products()->updateExistingPivot($product->id, ['installed'=>0]);
            }
        }
        return $this->successResponse(['id'=>$id]);

    }
    public function sorted(Request $request)
    {
        $validator = $this->laboratoryValidator($request->all());
        if($validator->fails()){
            return $this->validateErrorResponse($validator->errors()->all());
        }
        $user_laboratories=$request->user()->laboratories();
        $sorted_laboratories = $request->input('sorted_laboratories', []);
        foreach ($sorted_laboratories as $key => $laboratory) {
            $user_laboratories->where('id',$laboratory)->update(['sort'=>$key]);
        }

        return $this->successResponse(['sorted_laboratories'=>$sorted_laboratories]);
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
