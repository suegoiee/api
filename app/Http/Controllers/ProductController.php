<?php

namespace App\Http\Controllers;

use App\Repositories\ProductRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
class ProductController extends Controller
{	
    protected $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
	   $this->productRepository = $productRepository;
    }

    public function index(Request $request)
    {
       $product = $this->productRepository->getsWith(['tags','collections','faqs']);

        return $this->successResponse($product?$product:[]);
    }
    public function onShelves(Request $request)
    {
        $where = [];
        $with = [
                'tags'=>function($query){$query->select('name');},
                'plans'=>function($query){$query->where('active',1);}
            ];

        $user = $request->user();

        if($user){
            $with['users'] = function($query) use($user){$query->where('id',$user->id);};
        }

        if($request->has('type')){
            $where['type'] = $request->input('type');
        }

        $products = $this->productRepository->getsWithByStatus($with,$where);
        foreach ($products as $key => $product) {
            $product_user = $user ? $product->users()->where('id', $user->id)->first() : false;
            if($product_user){
                $product->owned = ($product_user->pivot->deadline==null || time() <= strtotime($product_user->pivot->deadline)) ? 1 : 0;
            }else{
                $product->owned = 0;
            }
            $product->month = 0 ;//+ $product->inflated;
            /*$orders = $product->orders;
            foreach ($orders as $key => $order) {
                $product->month += $order->pivot->quantity;
            }*/
        }
        $products->makeHidden(['status', 'created_at', 'updated_at', 'deleted_at','price','expiration','users','info_more','orders','faqs', 'inflated']);
        return $this->successResponse($products);
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
        $pathname = $request->input('pathname');
        if($pathname!=''){
            $product = $this->productRepository->getBy(['pathname'=>$request->input('pathname')]);
            if($product){
                return $this->validateErrorResponse([trans('product.The pathname_is_exists')]);
            }
        }
        $request_data = $request->only(['name','alias','model','column','info_short','info_more','type','status','faq', 'pathname','seo','date_range','inflated', 'category','single_options', 'single_type']);
        $request_data['inflated'] = isset($request_data['inflated']) && $request_data['inflated']!=''? $request_data['inflated']:0;
        $request_data['category'] = isset($request_data['category'])? $request_data['category']:null;
        $request_data['date_range'] = isset($request_data['date_range'])? $request_data['date_range']:'';
        $request_data['expiration'] = isset($request_data['expiration'])? $request_data['expiration']:0;
        $request_data['price'] = isset($request_data['price'])? $request_data['price']:0;
        $product = $this->productRepository->create($request_data);

        $tags = $request->input('tags',[]);
        $product->tags()->attach($tags);

        $experts = $request->input('experts',[]);
        $product->experts()->attach($experts);

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
                $new_plan = $product->plans()->create(['name'=>isset($plan['name'])?$plan['name']:'', 'price'=>isset($plan['price']) && $plan['price']!=''?$plan['price']:0,'expiration'=>$plan['expiration'], 'active'=>isset($plan['active'])?$plan['active']:0, 'introduction'=>isset($plan['plan_intro'])?$plan['plan_intro']:'', 'freecourses'=>isset($plan['free_courses'])?$plan['free_courses']:0]);
                if(array_key_exists('expert_affiliated_product_select', $plan)){
                    foreach($plan['expert_affiliated_product_select'] as $key => $solution){
                        $product->solutions()->create(['solution_product_id'=>$solution, 'product_prices_id'=>$new_plan->id]);
                    }
                }
            }else{
                $product->plans()->where('id', $plan['id'])->update(['name'=>isset($plan['name'])?$plan['name']:'', 'price'=>isset($plan['price']) && $plan['price']!=''?$plan['price']:0,'expiration'=>$plan['expiration'], 'active'=>isset($plan['active'])? $plan['active']:0, 'introduction'=>isset($plan['plan_intro'])?$plan['plan_intro']:'' ]);
            }
        }

        $faqs = $request->input('faqs',[]);
        foreach ($faqs as $key => $faq) {
            if(isset($faq['question'])||isset($faq['answer'])){
                $product->faqs()->create(['question'=>$faq['question'],'answer'=>$faq['answer']]);
            }
        }
        
        $affiliated_products = $request->input('affiliated_products',[]);
        $update_affiliated_products = [];
        foreach ($affiliated_products as $key => $affiliated_product) {
            $update_affiliated_products[$affiliated_product] = ['sort' => $key];
        }
        $product->affiliated_products()->attach($update_affiliated_products);

        $this->productUpdated($product);
        return $this->successResponse($product?$product:[]);
    }

    public function show(Request $request, $id)
    {
        
        $product = $this->productRepository->getWith($id,['tags','collections','faqs']);

        return $this->successResponse($product?$product:[]);
    }
    public function onShelf(Request $request, $id)
    {
        $with = [
            'tags',
            'collections'=>function($query){$query->orderBy('product_collections.sort');},
            'faqs',
            'plans'=>function($query){$query->where('active',1);},
            'affiliated_products'
        ];
        $user = $request->user();
        if($user){
            $with['users'] = function($query) use($user){$query->where('id',$user->id);};
        }
        $product = $this->productRepository->getWithByStatus($id, $with);
        if(!$product){
            $product = $this->productRepository->getBy(['pathname'=>$id,'status'=>1], $with);
            if(!$product){
                return $this->failedResponse(['message'=>[trans('product.product_is_not_exists')]]);
            }
        }
        $product_user = $user ? $product->users()->where('id', $user->id)->first() : false;
        if($product_user){
            $product->owned = ($product_user->pivot->deadline==null || time() <= strtotime($product_user->pivot->deadline)) ? 1 : 0;
        }else{
            $product->owned = 0;
        }
        $product->makeHidden(['status', 'created_at', 'updated_at', 'deleted_at','price','expiration','users']);
        return $this->successResponse($product);
    }

    public function edit($id)
    {
      
    }

    public function update(Request $request, $id)
    {
        $validator = $this->productUpdateValidator($request->all(), $id);
        if($validator->fails()){
            return $this->validateErrorResponse($validator->errors()->all());
        }
        $pathname = $request->input('pathname');
        if($pathname!=''){
            $product = $this->productRepository->getBy(['pathname'=>$request->input('pathname')]);
            if($product && $product->id != $id ){
                return $this->validateErrorResponse([trans('product.The pathname_is_exists')]);
            }
        }
        $request_data = $request->only(['name','alias','model','column','info_short','info_more','type','status','faq', 'pathname','seo','date_range', 'inflated', 'category','single_options', 'single_type']);
        
        $request_data['inflated'] = isset($request_data['inflated']) && $request_data['inflated']!=''? $request_data['inflated']:0;
        $request_data['category'] = isset($request_data['category'])? $request_data['category']:null;
        $request_data['model'] = isset($request_data['model']) ? $request_data['model']:'';
        $request_data['column'] = isset($request_data['column']) ? $request_data['column']:'';
        $request_data['info_more'] = isset($request_data['info_more']) ? $request_data['info_more']:'';
        $request_data['pathname'] = isset($request_data['pathname']) ? $request_data['pathname']:'';
        $request_data['seo'] = isset($request_data['seo']) ? $request_data['seo']:'';
        $request_data['date_range'] = isset($request_data['date_range']) ? $request_data['date_range'] : '';
        $data = array_filter($request_data, function($item){return $item!==null;});
        
        $product = $this->productRepository->update($id,$data);

        $tags = $request->input('tags',[]);
        $product->tags()->sync($tags);

        $experts = $request->input('experts',[]);
        $product->experts()->sync($experts);
        
        $collections = $request->input('collections',[]);
        $update_collections = [];
        foreach ($collections as $key => $collection) {
            $update_collections[$collection] = ['sort' => $key];
        }
        $product->collections()->sync($update_collections);
        
        $plans = $request->input('plans',[]);
        foreach ($plans as $key => $plan) {
            if($plan['id']==0){
                $new_plan = $product->plans()->create(['price'=>isset($plan['price'])?$plan['price']:0,'expiration'=>$plan['expiration'], 'active'=>isset($plan['active'])?$plan['active']:0, 'introduction'=>isset($plan['plan_intro'])?$plan['plan_intro']:'', 'freecourses'=>isset($plan['free_courses'])?$plan['free_courses']:0]);
                if(array_key_exists('expert_affiliated_product_select', $plan)){
                    foreach($plan['expert_affiliated_product_select'] as $solution){
                        $product->solutions()->where('product_prices_id', $plan['id'])->delete();
                    }
                    foreach($plan['expert_affiliated_product_select'] as $key => $solution){
                        $product->solutions()->create(['solution_product_id'=>$solution, 'product_prices_id'=>$new_plan->id]);
                    }
                }
                else{
                    $product->solutions()->where('product_prices_id', $plan['id'])->delete();
                }
            }else{
                $new_plan = $product->plans()->where('id', $plan['id'])->toBase()->update(['price'=>isset($plan['price'])?$plan['price']:0,'expiration'=>$plan['expiration'], 'active'=>isset($plan['active'])?$plan['active']:0, 'introduction'=>isset($plan['plan_intro'])?$plan['plan_intro']:'', 'freecourses'=>isset($plan['free_courses'])?$plan['free_courses']:0]);
                if(array_key_exists('expert_affiliated_product_select', $plan)){
                    foreach($plan['expert_affiliated_product_select'] as $solution){
                        $product->solutions()->where('product_prices_id', $plan['id'])->delete();
                    }
                    foreach($plan['expert_affiliated_product_select'] as $key => $solution){
                        $product->solutions()->create(['solution_product_id'=>$solution, 'product_prices_id'=>$plan['id']]);
                    }
                }
                else{
                    $product->solutions()->where('product_prices_id', $plan['id'])->delete();
                }
            }
        }
        $delete_solutions = $request->input('delete_solutions', []);
        foreach ($delete_solutions as $key => $delete_solution) {
            $product->plans()->where('id', $delete_solution)->delete();
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

        $affiliated_products = $request->input('affiliated_products',[]);
        $update_affiliated_products = [];
        foreach ($affiliated_products as $key => $affiliated_product) {
            $update_affiliated_products[$affiliated_product] = ['sort' => $key];
        }
        $product->affiliated_products()->sync($update_affiliated_products);

        $this->productUpdated($product);

        return $this->successResponse($product?$product:[]);
    }

    public function destroy(Request $request, $id)
    {
        $this->productRepository->delete($id);
        return $this->successResponse(['id'=>$id]);

    }

    public function tags(Request $request)
    {
        $where = [];
        $with = [
                'tags'=>function($query){$query->select('name');}
            ];
        $products = $this->productRepository->getsWithByStatus($with,$where);
        $tags=[];
        foreach ($products as $key => $product) {
            foreach ($product->tags as $key2 => $tag) {
                if(!in_array($tag->name, $tags)){
                    $tags[] = $tag->name;
                }
            }
        }
        
        return $this->successResponse(array_values($tags));
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
            'inflated'=>'numeric'
        ]);        
    }

    protected function productUpdateValidator(array $data, $id)
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
            'inflated'=>'numeric'
        ]);        
    }
    protected function productUpdated($product){
        if($product->type=='collection'){
            $laboratory = $product->laboratory;
            if($laboratory){
                $laboratory->update([
                    'category'=>$product->category,
                    'title'=>$product->name, 
                    'alias'=>$product->alias,
                    'pathname'=> $product->pathname,
                    'sort'=> 0,
                    'customized'=>0
                ]);
            }else{
                $laboratory = $product->laboratory()->create([
                    'user_id'=> 0,
                    'category'=> $product->category,
                    'title'=> $product->name, 
                    'alias'=>$product->alias,
                    'pathname'=> $product->pathname,
                    'sort'=> 0,
                    'customized'=> 0
                ]);
            }

            $collections_ids = [];
            foreach ($product->collections as $key => $collection_product) {
                $collection_sort = $collection_product->pivot->sort;
                $collections_ids[$collection_product->id] = ['sort'=>$collection_sort];           
            }
            $laboratory->products()->sync($collections_ids);

            return $laboratory;
        }
        return false;
    }
}
