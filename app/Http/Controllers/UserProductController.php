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
        $products = $request->user()->products()->with('faqs')->get()->makeHidden(['model','info_short','info_more','expiration','status','faq','created_at', 'updated_at', 'deleted_at', 'avatar_detail','pivot']);
        foreach ($products as $key => $product) {
            $product->installed = $product->pivot->installed;
            $product->deadline = $product->pivot->deadline;
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
            $product_data = $this->productRepository->get($product);
            $old_product = $user->products()->where('id',$product)->first();

            $old_deadline = $old_product ? $old_product->pivot->deadline : 0;

            $deadline = $this->getExpiredDate($product_data->expiration, $old_deadline);

            $installed = $old_product ? $old_product->pivot->installed : 0;
            $collections =[];
            if($product_data->type=='collection'){
                if($installed==0){
                    if($user->products()->where('id',$product_data->id)->count()==0){
                        $laboratory = $user->laboratories()->create(['title'=>$product_data->name,'customized'=>0]);
                        $this->create_avatar($laboratory, $product_data->avatar_small);
                        $laboratory->products()->syncWithoutDetaching($product_data->id);
                    }
                    $installed = 1;
                }
                $collections = $product_data->collections;
            }
            $products[$product_data->id] = ['deadline'=>$deadline,'installed'=>$installed];
            array_push($result,['id'=>$product_data->id, 'deadline'=>$deadline, 'installed'=>$installed, 'collections'=>$collections]);
        }
        $user->products()->syncWithoutDetaching($products);

        return $this->successResponse($result);
    }

    public function show(Request $request, $id)
    {
        
        $product = $request->user()->products()->with(['tags','collections','faqs'])->find($id);
        $product->installed = $product->pivot->installed;
        $product->deadline = $product->pivot->deadline;

        return $this->successResponse($product?$product->makeHidden(['model','column','info_short','info_more','expiration','status','faq','created_at', 'updated_at', 'deleted_at' , 'avatar_detail','pivot']):[]);
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
                $laboratory = $user->laboratories()->create(['title'=>$product->name, 'customized'=>0]);
                $this->create_avatar($laboratory, $product->avatar_small);
                $laboratory->products()->syncWithoutDetaching($product->id);
            }
        }
        $user->products()->updateExistingPivot($product->id,['installed'=>1]);

        return $this->successResponse(['message'=>[$product->name.' installed']]);
    }
    public function uninstall(Request $request, $id)
    {
        $user = $request->user();
        $product = $request->user()->products()->find($id);
        if(!$product){
            return $this->validateErrorResponse([trans('auth.permission_denied')]);
        }
        if($product->type=='collection'){
            $laboratory = $product->laboratories()->where('user_id',$user->id)->first();
            if($laboratory){
                $laboratory->delete();
            }
        }
        $request->user()->products()->updateExistingPivot($id,['installed'=>0]);


        return $this->successResponse(['message'=>[$product->name.' uninstalled']]);
    }

    public function update(Request $request, $id)
    {
        $validator = $this->productValidator($request->all());
        if($validator->fails()){
            return $this->validateErrorResponse($validator->errors()->all());
        }

        $request_data = $request->only(['deadline','installed']);
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
        ]);        
    }
    private function getExpiredDate($days, $expired = 0){
        if($days==0){
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
        return $date->addDays($days);
    }
    private function create_avatar($laboratory, $avatar){
        $contents = new File(storage_path('app/public/'.$avatar->path));
        $path = $this->createAvatar($contents, $laboratory->id, 'laboratories');
        $data = ['path' => $path,'type'=>'normal'];
        return $laboratory->avatars()->create($data);
    }
}
