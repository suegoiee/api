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
        $product = $this->productRepository->getsWith(['tags','collections','faqs']);

        return $this->successResponse($product?$product:[]);
    }
    public function onShelves()
    {
        $products = $this->productRepository->getsWithByStatus(['tags'=>function($query){$query->select('name');},'faqs','plans'=>function($query){$query->where('active',1);}])->makeHidden(['status', 'created_at', 'updated_at', 'deleted_at','price','expiration']);
        return $this->successResponse($products?$products:[]);
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

        $request_data = $request->only(['name','model','column','info_short','info_more','type','price','expiration','status','faq', 'pathname','seo']);
        $request_data['expiration'] = isset($request_data['expiration'])? $request_data['expiration']:0;
        $request_data['price'] = isset($request_data['price'])? $request_data['price']:0;
        $product = $this->productRepository->create($request_data);


        $tags = $request->input('tags',[]);
        $product->tags()->attach($tags);

        if( $product->type =='collection' ){
            $collections = $request->input('collections',[]);
            $update_collections = [];
            foreach ($collections as $key => $collection) {
                $update_collections[$collection] = ['sort' => $key];
            }
            $product->collections()->attach($update_collections);
        }
        $plans = $request->input('plans',[]);
        foreach ($plans as $key => $plan) {
            if($plan['id']==0){
                $product->plans()->create(['price'=>isset($plan['price'])?$plan['price']:0,'expiration'=>$plan['expiration'], 'active'=>isset($plan['active'])?$plan['active']:0 ]);
            }else{
                $product->plans()->where('id', $plan['id'])->update(['price'=>isset($plan['price'])?$plan['price']:0,'expiration'=>$plan['expiration'], 'active'=>isset($plan['active'])? $plan['active']:0 ]);
            }
        }

        $faqs = $request->input('faqs',[]);
        foreach ($faqs as $key => $faq) {
            if(isset($faq['question'])||isset($faq['answer'])){
                $product->faqs()->create(['question'=>$faq['question'],'answer'=>$faq['answer']]);
            }
        }
        
        return $this->successResponse($product?$product:[]);
    }

    public function show(Request $request, $id)
    {
        
        $product = $this->productRepository->getWith($id,['tags','collections','faqs']);

        return $this->successResponse($product?$product:[]);
    }
    public function onShelf(Request $request, $id)
    {
        
        $product = $this->productRepository->getWithByStatus($id, ['tags','collections'=>function($query){$query->orderBy('product_collections.sort');},'faqs','plans'=>function($query){$query->where('active',1);}]);
        if(!$product){
            $product = $this->productRepository->getBy(['pathname'=>$id,'status'=>1], ['tags','collections'=>function($query){$query->orderBy('product_collections.sort');},'faqs','plans'=>function($query){$query->where('active',1);}]);
            if(!$product){
                return $this->failedResponse(['message'=>[trans('product.product_is_not_exists')]]);
            }
        }
        $product->makeHidden(['status', 'created_at', 'updated_at', 'deleted_at','price','expiration']);
        $product->plans->makeHidden('id');
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

        $request_data = $request->only(['name','model','column','info_short','info_more','type','price','expiration','status','faq', 'pathname','seo']);
        $request_data['model'] = $request_data['model'] ? $request_data['model']:'';
        $request_data['column'] = $request_data['column'] ? $request_data['column']:'';
        $request_data['info_more'] = $request_data['info_more'] ? $request_data['info_more']:'';
        $request_data['pathname'] = $request_data['pathname'] ? $request_data['pathname']:'';
        $request_data['seo'] = $request_data['seo'] ? $request_data['seo']:'';
        $data = array_filter($request_data, function($item){return $item!==null;});
        
        $product = $this->productRepository->update($id,$data);

        $tags = $request->input('tags',[]);
        $product->tags()->sync($tags);
        
        $collections = $request->input('collections',[]);
        $update_collections = [];
        foreach ($collections as $key => $collection) {
            $update_collections[$collection] = ['sort' => $key];
        }
        $product->collections()->sync($update_collections);
        //$product->collections()->sync($collections);
        $plans = $request->input('plans',[]);
        foreach ($plans as $key => $plan) {
            if($plan['id']==0){
                $product->plans()->create(['price'=>isset($plan['price'])?$plan['price']:0,'expiration'=>$plan['expiration'], 'active'=>isset($plan['active'])?$plan['active']:0]);
            }else{
                $product->plans()->where('id', $plan['id'])->update(['price'=>isset($plan['price'])?$plan['price']:0,'expiration'=>$plan['expiration'], 'active'=>isset($plan['active'])?$plan['active']:0]);
            }
        }
        $faqs = $request->input('faqs',[]);
        $faq_ids = [];
        foreach ($faqs as $key => $faq) {
            if(!isset($faq['question']) && !isset($faq['answer'])){
                continue;
            }
            $faq['question'] = isset($faq['question']) ? $faq['question']:'';
            $faq['answer'] = isset($faq['answer']) ? $faq['answer']:'';
            if($faq['id']==0){
                $faq_data = $product->faqs()->create(['question'=>$faq['question'],'answer'=>$faq['answer']]);
            }else{
                $product->faqs()->where('id',$faq['id'])->update(['question'=>$faq['question'],'answer'=>$faq['answer']]);
                $faq_data = $product->faqs()->find($faq['id']);
            }
            array_push($faq_ids,$faq_data->id);
        }
        $product->faqs()->whereNotIn('id',$faq_ids)->delete();

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
            'column' => 'max:255',
            'api' => 'max:255',
            'info_short'=>'required|max:255',
            'info_more' => 'string',
            'type'=>'required|max:255',
            //'price'=>'required|numeric',
            'faq'=>'string',
        ]);        
    }

    protected function productUpdateValidator(array $data)
    {
        return Validator::make($data, [
            'name' => 'max:255',
            'model' => 'max:255',
            'column' => 'max:255',
            'info_short'=>'max:255',
            'info_more'=>'string',
            'type'=>'max:255',
            //'price'=>'numeric',
            'faq'=>'string',
        ]);        
    }
}
