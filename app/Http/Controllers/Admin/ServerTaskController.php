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
            if(!$laboratory->customized && !$laboratory->product_id){
                $product = $laboratory->products->first();
                $this->output_msg($laboratory->title);
                $laboratory->update(['product_id' => $product ? $product->id : 0]);
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
            $laboratory = $user->laboratories()->where('product_id',$product->id)->first();
            if(!$laboratory){
                $laboratory = $user->laboratories()->create(['title'=>$product->name, 'customized'=>0, 'product_id' => $product->id]);
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
    private function delete_avatar($laboratory, $forceDelete=false){
        if($laboratory->avatar){
            if(Storage::disk('public')->exists($laboratory->avatar->path)){
                $this->destroyAvatar($laboratory->avatar->path);
            }
            if($forceDelete){
                $laboratory->avatars()->withTrashed()->forceDelete();
            }else{
                $laboratory->avatars()->delete();
            }
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

        $laboratories = $laboratoryRepository->getsWith([],['product_id'=>250]);

        foreach ($laboratories as $key => $laboratory) {
            $laboratory->products()->detach(192);
            $laboratory->products()->attach([
                257 => ['sort' => 4],
                139 => ['sort' => 5],
                255 => ['sort' => 6],
                204 => ['sort' => 7],
                256 => ['sort' => 8],
                258 => ['sort' => 9],
            ]);
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
                $users[$order->user_id] = ['user'=>$order->user,'total_pay'=>$order->user->orders()->where('status',1)->where('price','<>',0)->where('created_at','>=', $date ? ($date.' 00:00:00'):$product->created_at)->sum('price')];
                $total_pay += $users[$order->user_id]['total_pay'];
                echo $order->user_id.','.($order->user->profile ? $order->user->profile->nickname:'').','.$users[$order->user_id]['total_pay'].'<br>';
            }
        }
        echo  $total_pay;
    }

    public function fixUserProductByOrder(OrderRepository $orderRepository)
    {
        set_time_limit(0);
        //$users = $userRepository->getsWith(['orders', 'products'],['mail_verified_at.<>'=>null]);
        $fix_product_ids["240"]=1;
        $fix_product_ids["250"]=12;
        foreach ($fix_product_ids as $fix_product_id => $fix_product_month) {
            $orders = $orderRepository->getsWith(['products'], ['status'=>1], [], ['products'=>function($query) use ($fix_product_id){$query->where('id',$fix_product_id);}]);
            foreach ($orders as $key => $order) {
                $user = $order->user;
                $deadline = date('Y-m-d H:i:s', strtotime($order->created_at . ' +'.$fix_product_month.' month'));
                $user->products()->updateExistingPivot($fix_product_id, ['deadline'=>$deadline]);
                echo $user->email.' '.$deadline.'<br/>';
            }
        }
    }
    public function fixAdditionUserProducts(UserRepository $userRepository)
    {
        set_time_limit(0);
        $users = $userRepository->getsWith([],['mail_verified_at.<>'=>null]);
        foreach($users as $key =>$user)
        {
            $product188 = $user->products()->where('id',188)->first();
            $product233 = $user->products()->where('id',233)->first();
            if($product188 && $product233)
            {
                echo $user->id.' '.$user->email.' : 188=>'.$product188->pivot->deadline.' : 233=>'.$product233->pivot->deadline.'<br/>';
                $orders = $user->orders()->whereHas('products',function($query){$query->whereIn('id',[188,233]);})->get();
                foreach($orders as $key2 =>$order){
                    echo $order->created_at.'<br/>';
                    foreach ($order->products as $key => $product) {
                        echo $product->id.' '.$product->quantity.'<br/>';
                    }
                }
            }
            
        }      
    
    }
    public function fixProdcutCategory(ProductRepository $productRepository)
    {
        set_time_limit(0);
        $products = $productRepository->gets();
        foreach($products as $key =>$product)
        {
            echo $product->name.'<br/>';
            if($product->type == 'collection'){
                $productRepository->update($product->id, ['category'=>3]);
            }else{
                $productRepository->update($product->id, ['category'=>1]);
            }

        }      
    
    }

    public function newLaboratory(ProductRepository $productRepository, LaboratoryRepository $laboratoryRepository)
    {
        set_time_limit(0);
        $products = $productRepository->getsWith(['laboratory']);
        foreach ($products as $key => $product) {
            if($product->category != 1){
                if($product->laboratory){
                    if(!$laboratoryRepository->getBy(['user_id'=>'0', 'product_id'=>$product->id])){
                        $laboratory_data = ['title'=>$product->name, 'category'=>$product->category, 'pathname'=>$product->pathname, 'sort'=>$product->sort, 'customized'=>0, 'user_id'=>0];
                        $laboratory = $product->laboratory()->create($laboratory_data);
                        $this->create_avatar($laboratory, $product->avatar_small);
                        $collections_ids = [];
                        foreach ($product->collections as $key => $collection_product) {
                            $collection_sort = $collection_product->pivot->sort;
                            $collections_ids[$collection_product->id] = ['sort'=>$collection_sort];           
                        }
                        $laboratory->products()->sync($collections_ids);
                        echo $laboratory->title.'<br/>';
                    }
                }
            }
        }
    }
    public function linkNewLaboratory(UserRepository $userRepository, LaboratoryRepository $laboratoryRepository)
    {
        set_time_limit(0);
        $users = $userRepository->gets();
        foreach($users as $key =>$user){
            echo $user->email.'<br/>';
            foreach ($user->laboratories as $key2 => $laboratory) {
                if($laboratory->customized==0){
                    echo 'link new '.$laboratory->title.'<br/>';
                    $new_laboratory = $laboratoryRepository->getBy(['user_id'=>'0', 'product_id'=>$laboratory->product_id]);
                    if($new_laboratory){
                        echo 'New laboratory '.$new_laboratory->title.'<br/>';
                        $user->master_laboratories()->syncWithoutDetaching($new_laboratory->id,['sort'=>$laboratory->sort]);
                    }

                    echo '  Remove '.$laboratory->title.' all products...<br/>';
                    $laboratory->products()->detach();

                    echo '  Remove '.$laboratory->title.' avatar file...<br/>';
                    $this->delete_avatar($laboratory, true);

                    echo '  Remove '.$laboratory->title.'...<br/>';
                    $laboratory->forceDelete();

                    ob_flush(); 
                    flush();
                }
            }
        }
    }
    public function clearDeletedLaboratory(UserRepository $userRepository)
    {
        set_time_limit(0);
        $users = $userRepository->gets();
        foreach($users as $key =>$user){
            echo $user->id.' '.$user->email.'<br/>';
            $laboratories = $user->laboratories()->withTrashed()->get();
            foreach ($laboratories as $key2 => $laboratory) {
                if($laboratory->trashed()){
                    echo $laboratory->title.'<br/>';

                    echo '  Remove '.$laboratory->title.' all products...<br/>';
                    $laboratory->products()->detach();

                    echo '  Remove '.$laboratory->title.' avatar file...<br/>';
                    $laboratory->avatars()->withTrashed()->forceDelete();

                    echo '  Remove '.$laboratory->title.'...<br/>';
                    $laboratory->forceDelete();
                }
            }
        }
    }
    public function FixLabProductDuplicated(UserRepository $userRepository)
    {
        set_time_limit(0);
        $users = $userRepository->gets();
        foreach($users as $key =>$user){
            echo $user->id.' '.$user->email.'<br/>';
            $laboratories = $user->laboratories()->get();
            foreach ($laboratories as $key2 => $laboratory) {
                if($laboratory->product_id != 0){
                    if(!$user->products()->find($laboratory->product_id)){
                        echo '  Remove '.$laboratory->title.'...<br/>';
                        $laboratory->forceDelete();
                    }else if($laboratory->customized == 1){
                        echo $laboratory->title.'<br/>';
                        echo '  Adjust '.$laboratory->title.' product id...<br/>';
                        $laboratory->update(['product_id'=>0]);
                    }
                }
            }
            $laboratories = $user->master_laboratories()->get();
            foreach ($laboratories as $key2 => $laboratory) {
                if(!$user->products()->find($laboratory->product_id)){
                    echo '  Remove relation '.$laboratory->title.'...<br/>';
                    $user->master_laboratories()->detach($laboratory->id);
                }
            }
        }
    }
    public function installUserProduct(UserRepository $userRepository, $email=false)
    {
        set_time_limit(0);
        if($email){
            $users = $userRepository->getsWith([],['email'=>$email]);
        }else{
            $users = $userRepository->getsWith([],['mail_verified_at.<>'=>null]);
        }
        foreach($users as $key =>$user){
            $product_ids = [];
            foreach ($user->products as $key => $product) {
                if($product->category=='3' && $product->laboratory){
                    array_push($product_ids, $product->laboratory->id);
                }
            }
            echo $user->email.' '. count($product_ids).'<br/>';
            $user->master_laboratories()->syncWithoutDetaching($product_ids);
        }
    }

    public function listProductPaymentUserByPlan(OrderRepository $orderRepository, ProductRepository $productRepository, $product_id, $plan=0)
    {
        set_time_limit(0);
        $orders = $orderRepository->getsWith([],['status'=>1],[],['products'=>function($query)use ($product_id){ $query->where('id', $product_id);}]);
        $users = [];
        foreach ($orders as $key => $order) {
            if(!isset($users[$order->user_id])){
                $product = $order->products->where('id',$product_id)->first();
                if($product->quantity == $plan && strtotime($order->created_at) > strtotime(date('Y-m-d').' -1 year')){
                    $users[$order->user_id] = ['user'=>$order->user,'product'=>$product];
                    echo $order->user_id.','.($order->user_nickname).','.($order->user_email).','.$product->name.'<br>';
                }
            }
        }
    }

    public function updateSingleSetting(ProductRepository $productRepository)
    {
        set_time_limit(0);
        $http = new \GuzzleHttp\Client;
        $products = $productRepository->getsWith([],["type"=>'single']);
        foreach($products as $key=>$product){
            $single_options = [];
            try{
                $response = $http->request('get', 'https://cronjob.uanalyze.com.tw/fetch/'.$product->model.'/1101');
            }catch (\GuzzleHttp\Exception\ClientException $e){
                try{
                    $single_options['noStock'] = true;
                    $response = $http->request('get', 'https://cronjob.uanalyze.com.tw/fetch/'.$product->model);
                }catch (\Exception $e){
                    continue;
                }
            }catch (\Exception $e){
                continue;
            }
                        echo $product->id.' '.$product->name.'<br/>'.PHP_EOL;
            
            $responseData = json_decode((string) $response->getBody(), true);
            if($responseData['status']=='OK' && ( isset($responseData['type']) || ( isset($responseData['data']) && isset($responseData['data']['type'])))){
                $dataType = array_key_exists("type", $responseData) ? 
                                $responseData['type'] : $responseData['data']['type'];
                echo $dataType.'<br/>'.PHP_EOL;
                switch($dataType){
                    case 'table_chart':         $type = 'comboChart';
                    break;
                    case 'score':
                    case 'rating':          $type = 'score';
                    break;
                    case 'CompanyInfo':         $type = 'companyInfo';
                    break;
                    case 'KLine':
                    case 'kline':           $type = 'kLine';
                    break;
                    case 'link_list':           $type = 'news';
                    break;
                    case 'rankings':            $type = 'selection';
                    break;
                    case 'account_table':
                    case 'rankings_table':
                    case 'sorting_table':   $type = 'table';
                    break;
                    case 'chart':
                    case 'line':
                        $set_default_range_mode = false;
                        $chart_data = [];
                        if(array_key_exists('data',$responseData['data'])){
                            foreach ($responseData['data']['data'] as $model => $data) {
                                echo $model.'<br/>'.PHP_EOL;
                                if(is_array($data) && array_key_exists('Style', $data)){
                                    echo $data['Style'].'<br/>'.PHP_EOL;
                                    if($data['Style'] == 'area'){
                                        $set_default_range_mode = true;
                                    }
                                    $single_options['chart_type'] = $data['Style'];
                                    $chart_data[$model] = $data['Style'];
                                }
                            }
                        }
                        $type = 'chart';
                        if($set_default_range_mode){
                            $single_options['default_range_mode'] = 'all';
                        }
                        if(count($chart_data)){
                            $single_options['data'] = $chart_data;
                        }
                    break;
                    default:                    $type = $dataType;break;
                }
                if($product->model == 'CompanyInfo'){
                    $type = 'companyInfo';
                }

                $product->update(['single_options'=>json_encode($single_options), 'single_type'=>$type]);
            }else{
                echo $product->id.' '.$product->name.'<br/>'.PHP_EOL;
                continue;
            }
        }
    }
     public function updateFreeLab(ProductRepository $productRepository)
    {
        set_time_limit(0);
        $products = $productRepository->getsWith([],["id.in"=>[83, 96, 108, 119, 120, 122, 134, 141, 142, 152, 155]]);
        foreach($products as $key=>$product){
            $product->update(['category'=>0]);
            $product->laboratory->update(['category'=>0]);
        }
    }
    public function fixUserLabSamePathname(ProductRepository $productRepository, LaboratoryRepository $laboratoryRepository)
    {
        set_time_limit(0);
        $product = $productRepository->get(233);
        foreach($product->users as $key2 => $user){
            if(strtotime($user->pivot->deadline) >= time()){
                $user->master_laboratories()->syncWithoutDetaching($product->laboratory->id);
            }
        }
        $product = $productRepository->get(188);
        $laboratory = $product->laboratory;
        foreach($laboratory->users as $key2 => $user){
            $product = $user->products()->find($laboratory->product_id);
            if($product && strtotime($product->pivot->deadline) < time()){
                $user->master_laboratories()->detach($laboratory->id);
            }
        }
    }
    public function fixSameProduct(ProductRepository $productRepository, LaboratoryRepository $laboratoryRepository)
    {
        set_time_limit(0);
        $product = $productRepository->get(188);
        foreach($product->users as $key2 => $user){
            if(strtotime($user->pivot->deadline) >= time()){
                echo $user->pivot->deadline.' '.$user->id.' '.$user->email.'<br/>';
            }
        }
    }
}
