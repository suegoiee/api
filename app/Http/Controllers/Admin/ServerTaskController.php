<?php
namespace App\Http\Controllers\Admin;
use Illuminate\Support\Facades\DB;
use App\Repositories\UserRepository;
use App\Repositories\LaboratoryRepository;
use App\Repositories\ProductRepository;
use App\Repositories\Repository;
use App\Repositories\StockRepository;
use App\Repositories\PromocodeRepository;
use App\Repositories\OrderRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\User;
use App\ForumUser;
use App\Article;
use Illuminate\Http\File;
use Storage;
use App\Traits\ImageStorage;
use Illuminate\Support\Facades\Hash;

class ServerTaskController extends AdminController
{	
    use ImageStorage;
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
    public function seedUsers(Request $requset, ProductRepository $productRepository)
    {
        $product = $productRepository->get(69);
        $collections_ids = [];
        foreach ($product->collections as $key => $collection_product) {
                $collection_sort = $collection_product->pivot->sort;
                $collections_ids[$collection_product->id] = ['sort'=>$collection_sort];
                }

        for ($i=0; $i <50 ; $i++) {
            $email = '8880113'.str_pad(($i+1),2,"0",STR_PAD_LEFT).'@guest.com';
            $user = User::where('email',$email)->first();
            if(!$user){
                $user = User::create([
                    'email'=>'8880113'.str_pad(($i+1),2,"0",STR_PAD_LEFT).'@guest.com',
                    'password' => Hash::make('888888'),
                    'mail_verified_at' => date('Y-m-d H:i:s'),
                ]);
            }
            $profile = $user->profile;
            if(!$profile){
                $user->profile()->create(['nickname'=>'Guest']);
            }
            $user->products()->sync([$product->id => ['deadline'=>'2019-01-13 17:00:00','installed'=>1]]);
            $laboratory = $user->laboratories()->where('collection_product_id',$product->id)->first();
            if(!$laboratory){
                $laboratory = $user->laboratories()->create(['title'=>$product->name, 'customized'=>0, 'collection_product_id' => $product->id]);
                $this->create_avatar($laboratory, $product->avatar_small);
            }
            $laboratory->products()->sync($collections_ids);
            echo $email.' '.json_encode($laboratory).' '.'<br>';
        }
    }
    public function destroySeedUsers(Request $requset)
    {
        for ($i=0; $i <50 ; $i++) {
            $email = '8880113'.str_pad(($i+1),2,"0",STR_PAD_LEFT).'@guest.com';
            $user = User::where('email',$email)->first();
            if($user){
                $user->profile()->delete();
                $laboratories = $user->laboratories;
                foreach ($laboratories as $key => $laboratory) {
                    $this->delete_avatar($laboratory);
                }
                $user->laboratories()->forceDelete();
                $user->products()->detach();
                $user->forceDelete();
                echo $email.' '.'<br>';
            }
        }
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
    private function delete_avatar($laboratory){
        if($laboratory->avatar){
            if(Storage::disk('public')->exists($laboratory->avatar->path)){
                $this->destroyAvatar($laboratory->avatar->path);
            }
            $laboratory->avatars()->delete();
            return true;
        }
        return false;
    }
    public function importUsersToForum()
    {
        set_time_limit(0);
        $users  = User::get();
        foreach ($users as $key => $user) {
            if(ForumUser::where('email', $user->email)->count()==0){
                echo ($user->profile ? $user->profile->nickname : $user->email).'<br>';
                ForumUser::create([
                    'name' => $user->profile ? $user->profile->nickname : $user->email,
                    'email' => $user->email,
                    'username' => $user->profile ? $user->profile->nickname : $user->email,
                    'github_id' => '',
                    'github_username' => '',
                    'confirmation_code' => null,
                    'is_socialite'=>$user->is_socialite,
                    'confirmed'=>$user->mail_verified_at ? 1 : 0,
                    //'password'=>$hasher->make($this->password),
                    'password'=> $user->getAuthPassword(),
                    'type' => 1,
                    'remember_token' => '',
                ]);
            }
        }
    }

    public function importArchivesToForum()
    {
        set_time_limit(0);
        $articles = Article::get();
        $archives  = [];
        foreach ($articles as $key => $article) {
            echo $article->title.'<br>';
            array_push($archives, ['author_id'=>0, 'subject'=>$article->title,'slug'=>$article->generateUniqueSlug(), 'body'=>$article->content,'solution_reply_id'=>0, 'created_at'=>$article->created_at ,'updated_at'=>$article->updated_at]);
        }
        DB::connection('mysql_3')->table('archives')->insert($archives);
    }

    public function updateLaboratoryProduct(LaboratoryRepository $laboratoryRepository)
    {
        set_time_limit(0);
        $laboratories = $laboratoryRepository->getsWith([],['collection_product_id'=>188]);
        foreach ($laboratories as $key => $laboratory) {
            $laboratory->products()->attach([
                230 => ['sort' => 4],
                232 => ['sort' => 3]
            ]);
            $laboratory->products()->updateExistingPivot(172, ['sort' => 5]);
            echo $laboratory->title.' '.$laboratory->user_id.'<br/>';
        }

    }

    public function countUserPayment(OrderRepository $orderRepository, ProductRepository $productRepository, $product_id, $date=false)
    {
        set_time_limit(0);
        $orders = $orderRepository->getsWith([],['price'=>0,'status'=>1],[],['products'=>function($query)use ($product_id){ $query->where('id', $product_id);}]);
        $users = [];
        $total_pay = 0;
        $product = $productRepository->get($product_id);
        foreach ($orders as $key => $order) {
            if(!isset($users[$order->user_id])){
                $users[$order->user_id] = ['user'=>$order->user,'total_pay'=>$order->user->orders()->where('status',1)->where('price','<>',0)->where('create_at','>=', $date ? ($date.' 00:00:00'):$product->created_at)->sum('price')];
                $total_pay += $users[$order->user_id]['total_pay'];
                echo $order->user_id.','.($order->user->profile ? $order->user->profile->nickname:'').','.$users[$order->user_id]['total_pay'].'<br>';
            }
        }
        echo  $total_pay;
    }
}
