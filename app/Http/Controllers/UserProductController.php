<?php

namespace App\Http\Controllers;

use Storage;
use Illuminate\Http\File;
use App\Traits\ImageStorage;
use App\Traits\OauthToken;
use App\User;
use Carbon\Carbon;
use App\Repositories\ProductRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserProductController extends Controller
{	
    use OauthToken, ImageStorage;
    protected $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
	   $this->productRepository = $productRepository;
    }

    public function index(Request $request)
    {
        $products = $request->user()->products()->with(['faqs','plans'=>function($query){
            $query->where('active',1);
        }])->orderBy('product_user.sort', 'asc')->get()->makeHidden(['model','info_short','info_more','expiration','status','faq','created_at', 'updated_at', 'deleted_at', 'avatar_detail','pivot','price']);
        foreach ($products as $key => $product) {
            $product->installed = $product->pivot->installed;
            $product->deadline = $product->pivot->deadline ? $product->pivot->deadline:0;
            $product->sort = $product->pivot->sort;
            $product->faqs = $product->faqs;
        }
        
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
        $user = User::find($request->input('user_id'));
        $_products = $request->input('products',[]);
        $products = [];
        $result = [];
        foreach ($_products as $key => $product) {
            $quantity = isset($product['quantity'])? (int)$product['quantity'] : 1;
            $product_data = $this->productRepository->get($product["id"]);

            $old_product = $user->products()->where('id',$product["id"])->first();
            $old_deadline = $old_product ? $old_product->pivot->deadline : 0;
            $product_plan = $product_data->plans()->where('expiration', $quantity)->first();
            if(!$product_plan){
                continue;
            }
            $expiration = $product_plan->expiration;
            $deadline = $this->getExpiredDate($expiration, $old_deadline);
            $installed = $old_product ? $old_product->pivot->installed : 0;
            $collections_ids = [];
            $collection_products = [];
            $collections =[];
            if($product_data->type=='collection'){
                if($installed==0){
                    if($user->products()->where('id',$product_data->id)->count()==0){
                        $customized = 0;
                        $laboratory = $user->laboratories()->create(['title'=>$product_data->name, 'customized'=>$customized, 'collection_product_id' => $product_data->id]);
                        $this->create_avatar($laboratory, $product_data->avatar_small);
                        if($customized ==1){
                            foreach ($product_data->collections as $key => $collection_product) {
                                $old_collection_product = $user->products()->where('id',$collection_product->id)->first();
                                $old_collection_deadline = $old_collection_product ? $old_collection_product->pivot->deadline : 0;
                                $collection_deadline = $this->getExpiredDate($expiration, $old_collection_deadline);
                                $collection_installed = 1;

                                $collection_sort = $collection_product->pivot->sort;

                                $collections_ids[$collection_product->id] = ['sort'=>$collection_sort];

                                $collection_products[$collection_product->id] = ['deadline'=>$collection_deadline,'installed'=>$collection_installed];
                            }
                            $laboratory->products()->syncWithoutDetaching($collections_ids);
                        }else{
                            foreach ($product_data->collections as $key => $collection_product) {
                                $collection_sort = $collection_product->pivot->sort;

                                $collections_ids[$collection_product->id] = ['sort'=>$collection_sort];
                                //array_push($collections_ids, $collection_product->id);
                            }
                            $laboratory->products()->sync($collections_ids);
                        }
                    }
                    $installed = 1;
                }
                $collections = $product_data->collections;
            }
            $products[$product_data->id] = ['deadline'=>$deadline,'installed'=>$installed];
            
            array_push($result,['id'=>$product_data->id, 'deadline'=>$deadline, 'installed'=>$installed, 'collections'=>$collections,'msg'=>$expiration]);
        }
        $user->products()->syncWithoutDetaching($products);

        return $this->successResponse($result);
    }

    public function show(Request $request, $id)
    {
        
        $product = $request->user()->products()->with(['tags','collections','faqs','plans'=>function($query){
            $query->where('active',1);
        }])->find($id);
        $product->installed = $product->pivot->installed;
        $product->deadline = $product->pivot->deadline ? $product->pivot->deadline:0;
        $product->sort = $product->pivot->sort;

        return $this->successResponse($product?$product->makeHidden(['model','column','info_short','info_more','expiration','status','faq','created_at', 'updated_at', 'deleted_at' , 'avatar_detail','pivot','price']):[]);
    }

    public function edit($id)
    {
      
    }

    public function install(Request $request, $id)
    {
        $user = $request->user();
        $product = $user->products()->find($id);
        if(!$product){
            return $this->validateErrorResponse([trans('auth.permission_denied')]);
        }
        if($product->type=='collection'){
            if($product->pivot->installed==0){
                $customized = 0;
                $laboratory = $user->laboratories()->create(['title'=>$product->name, 'customized'=>$customized, 'collection_product_id' => $product->id ]);
                $this->create_avatar($laboratory, $product->avatar_small);
                $collection_product_ids = [];
                foreach ($product->collections as $key => $collection_product) {
                    $collection_sort = $collection_product->pivot->sort;
                    $collections_ids[$collection_product->id] = ['sort'=>$collection_sort];
                    //array_push($collection_product_ids, $collection_product->id);
                }
                $laboratory->products()->syncWithoutDetaching($collection_product_ids);
            }
        }
        $user->products()->updateExistingPivot($product->id,['installed'=>1]);

        return $this->successResponse(['message'=>[$product->name.' installed'], 'installed'=>1]);
    }
    public function uninstall(Request $request, $id)
    {
        $user = $request->user();
        $product = $request->user()->products()->find($id);
        if(!$product){
            return $this->validateErrorResponse([trans('auth.permission_denied')]);
        }
        if($product->type=='collection'){
            $laboratory = $user->laboratories()->where('collection_product_id', $product->id)->first();
            
            if($laboratory){
                $laboratory->delete();
            }

        }
        $request->user()->products()->updateExistingPivot($id,['installed'=>0]);


        return $this->successResponse(['message'=>[$product->name.' uninstalled'],'install'=>0]);
    }

    public function update(Request $request, $id)
    {
        $validator = $this->productValidator($request->all());
        if($validator->fails()){
            return $this->validateErrorResponse($validator->errors()->all());
        }

        $request_data = $request->only(['deadline','installed','sort']);
        $data = array_filter($request_data, function($item){return $item!=null;});
        $request->user()->products()->updateExistingPivot($id, $data);

        return $this->successResponse($request_data?$request_data:[]);
    }

    public function cancel(Request $request)
    {
        $user = User::find($request->input('user_id'));
        $_products = $request->input('products',[]);
        $products = [];
        $result = [];
        foreach ($_products as $key => $product) {
            $quantity = isset($product['quantity'])? (int)$product['quantity'] : 1;
            $product_data = $this->productRepository->get($product["id"]);

            $old_product = $user->products()->where('id',$product["id"])->first();
            $old_deadline = $old_product ? $old_product->pivot->deadline : 0;
            $product_plan = $product_data->plans()->where('expiration', $quantity)->first();
            if(!$product_plan){
                continue;
            }
            $expiration = $product_plan->expiration;
            $deadline = $this->getExpiredDateBack($expiration, $old_deadline);
            $installed = $old_product ? $old_product->pivot->installed : 0;
            $collections_ids = [];
            $collection_products = [];
            $collections =[];
            if($product_data->type=='collection'){
                if($installed==0){
                    if($user->products()->where('id',$product_data->id)->count()==0){
                        $customized = 0;
                        if($customized ==1){
                            foreach ($product_data->collections as $key => $collection_product) {
                                $old_collection_product = $user->products()->where('id',$collection_product->id)->first();
                                $old_collection_deadline = $old_collection_product ? $old_collection_product->pivot->deadline : 0;
                                $collection_deadline = $this->getExpiredDateBack($expiration, $old_collection_deadline);
                                $collection_installed = 1;
                                $collection_sort = $collection_product->pivot->sort;
                                $collections_ids[$collection_product->id] = ['sort'=>$collection_sort];
                                $collection_products[$collection_product->id] = ['deadline'=>$collection_deadline];
                            }
                            $laboratory->products()->syncWithoutDetaching($collections_ids);
                        }
                    }
                    $installed = 1;
                }
                $collections = $product_data->collections;
            }
            $products[$product_data->id] = ['deadline'=>$deadline];
            
            array_push($result,['id'=>$product_data->id, 'deadline'=>$deadline,'collections'=>$collections,'msg'=>$expiration]);
        }
        $user->products()->syncWithoutDetaching($products);

        return $this->successResponse($result);
    }

    public function sorted(Request $request)
    {
        $validator = $this->productValidator($request->all());
        if($validator->fails()){
            return $this->validateErrorResponse($validator->errors()->all());
        }
        $user_products = $request->user()->products();
        $sorted_products = $request->input('sorted_products', []);
        foreach ($sorted_products as $key => $product) {
            if($user_products->where('id',$product)->count()==0){
                return $this->failedResponse(['message'=>['The product is invalid']]);
            }
            $user_products->updateExistingPivot($product, ['sort'=>$key]);
        }

        return $this->successResponse(['sorted_products'=>$sorted_products]);
    }

    public function destroy(Request $request, $id)
    {
        $request->user()->products()->detach($id);
        return $this->successResponse(['id'=>$id]);

    }

    protected function productValidator(array $data)
    {
        return Validator::make($data, [
            'products.*.id' => 'exists:products,id',
        ]);        
    }
    private function getExpiredDate($months, $expired = 0){
        if($months==0){
            return null;
        }
        $now = Carbon::now('Asia/Taipei');
        if($expired){
            $expired_date = Carbon::parse($expired);
            $date = $expired_date->lt($now) ? $now : $expired_date;
        }else{
            $date = $now;
        }
        $date->hour = 0;
        $date->minute = 0;
        $date->second = 0;
        return $date->addMonths($months);
    }
    private function getExpiredDateBack($months, $expired = 0){
        if($months==0){
            return null;
        }
        $now = Carbon::now('Asia/Taipei');
        if($expired){
            $expired_date = Carbon::parse($expired);
            $date = $expired_date;
        }else{
            $date = $now;
        }
        $date->hour = 0;
        $date->minute = 0;
        $date->second = 0;
        return $date->subMonths($months);
    }
    private function create_avatar($laboratory, $avatar){
        if($avatar){
            if(Storage::disk('public')->exists($avatar->path)){
                $contents = new File(storage_path('app/public/'.$avatar->path));
                $path = $this->createAvatar($contents, $laboratory->id, 'laboratories');
                $data = ['path' => $path,'type'=>'normal'];
                return $laboratory->avatars()->create($data);
            }
        }
        return false;
    }
}
