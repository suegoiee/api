<?php
namespace App\Http\Controllers\Admin;
use Illuminate\Support\Facades\DB;
use App\Repositories\UserRepository;
use App\Repositories\LaboratoryRepository;
use App\Repositories\ProductRepository;
use App\Repositories\Repository;
use App\Repositories\StockRepository;
use App\Repositories\PromocodeRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ServerTaskController extends AdminController
{	
    public function __construct()
    {

    }
    public function flatLaboratoriesProducts(LaboratoryRepository $laboratoryRepository)
    {
        $laboratories = $laboratoryRepository->gets();
        foreach ($laboratories as $key => $laboratory) {
            if($laboratory->user_id !='0'){
                continue;
            }
            if(!$laboratory->customized && !$laboratory->collection_product_id){
                $product = $laboratory->products->first();
                $this->output_msg($laboratory->title);
                $laboratory->update(['collection_product_id' => $product ? $product->id : 0]);
                $laboratory_products = [];
                foreach ($product->collections as $key => $collection_product) {
                    array_push($laboratory_products, $collection_product->id);
                }
                $laboratory->products()->sync($laboratory_products);
            }
        }
    }
    private function output_msg($msg){
        echo $msg.'<br>';
        ob_flush(); 
        flush();
    }
    public function clearOAuthTokenTable(){
        DB::table('oauth_access_tokens')->truncate();
        DB::table('oauth_refresh_tokens')->truncate();
        echo 'clear table success!';
    }
    public function transCompanyIndustries(StockRepository $stockRepository){
        $stocks = $stockRepository->gets();
        foreach ($stocks as $key => $stock) {
            echo $stock->stock_name.': '.$stock->stock_industries.PHP_EOL;
            switch ($stock->stock_industries) {
                case 'ci':
                    $stockRepository->update($stock->no, ['stock_industries'=>1]);
                    break;
                case 'basi':
                    $stockRepository->update($stock->no, ['stock_industries'=>2]);
                    break;
                case 'bd':
                    $stockRepository->update($stock->no, ['stock_industries'=>3]);
                    break;
                case 'fh':
                    $stockRepository->update($stock->no, ['stock_industries'=>4]);
                    break;
                case 'ins':
                    $stockRepository->update($stock->no, ['stock_industries'=>5]);
                    break;
                case 'mim':
                    $stockRepository->update($stock->no, ['stock_industries'=>6]);
                    break;
                default:
                    $stockRepository->update($stock->no, ['stock_industries'=>0]);
                break;
            }
        }
    }
    public function extendProductExpired(ProductRepository $productRepository, Request $request)
    {
        $id = $request->input('product_id',0);
        $extend = $request->input('extend_date',0);
        if($id){
            $product = $productRepository->getWith($id, ['users']);
            echo $product->name.'<br>';
            foreach ($product->users as $key => $user) {
                echo $user->email.': '.($user->profile? $user->profile->name:'').'[ deadline:'.$user->pivot->deadline.' =>'.date('Y-m-d H:i:s', strtotime($user->pivot->deadline.' +1 day')).']<br>';
                $user->pivot->deadline = date('Y-m-d H:i:s', strtotime($user->pivot->deadline.' +1 day'));
                $user->pivot->save();
            }
        }
    }
    public function verifiedFBUser(UserRepository $userRepository)
    {
        $users = $userRepository->getsWith([],['is_socialite'=>1]);
        foreach ($users as $key => $user) {
            echo $user->email.': '.($user->profile? $user->profile->name:'').'[ mail_verified_at:'.date('Y-m-d H:i:s').']<br>';
                $user->mail_verified_at = date('Y-m-d H:i:s');
                $user->save();
            }
    }
    public function addProductPlans(ProductRepository $productRepository)
    {
        $products = $productRepository->gets();
        foreach ($products as $key => $product) {
            echo $product->name.' : price=>'.$product->price.', expiration=>'.$product->expiration.'<br>';
            $product->plans()->delete();
            if($product->price==0){ 
                $product->plans()->create(['price'=>0,'expiration'=>($product->expiration==8888? 0:$product->expiration), 'active'=>1]);
                $product->plans()->create(['price'=>0,'expiration'=>1, 'active'=>0]);
                $product->plans()->create(['price'=>0,'expiration'=>6, 'active'=>0]);
                $product->plans()->create(['price'=>0,'expiration'=>12, 'active'=>0]);
            }else{
                $product->plans()->create(['price'=>0,'expiration'=>0, 'active'=>0]);
                $product->plans()->create(['price'=>$product->price,'expiration'=>1, 'active'=>1]);
                $product->plans()->create(['price'=>$product->price*6,'expiration'=>6, 'active'=>1]);
                $product->plans()->create(['price'=>$product->price*12,'expiration'=>12, 'active'=>1]);
            }
        }
    }
    public function addProductToPromocode(PromocodeRepository $promocodeRepository)
    {
        $promocodes = $promocodeRepository->getsWith([],['offer'=>3800]);
        foreach ($promocodes as $key => $promocode) {
            if($promocode->products()->count()==0){
                echo $promocode->name.PHP_EOL;
               $promocode->products()->sync(69);
            }
        }
    }
}
