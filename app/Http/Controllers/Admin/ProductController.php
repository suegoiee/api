<?php
namespace App\Http\Controllers\Admin;
use App\Notifications\ProductReceive;
use App\Repositories\TagRepository;
use App\Repositories\ProductRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Lang;

class ProductController extends AdminController
{	
    protected $tagRepository;
    protected $userRepository;
    public function __construct(Request $request, ProductRepository $productRepository, TagRepository $tagRepository, UserRepository $userRepository)
    {
        parent::__construct($request);
        $this->moduleName='product';
        $this->moduleRepository = $productRepository;
        $this->tagRepository = $tagRepository;
        $this->userRepository = $userRepository;
    }

    public function index(Request $request)
    {
        $query_string=[];
        $where=[];
        if($request->has('status')){
            $where['status'] = $request->input('status',1);
            $query_string['status'] = $request->input('status',1);
        }else if($request->has('type')){
            $where['type'] = $request->input('type','collection');
            $query_string['type'] = $request->input('type','collection');
        }else{
            $where['type'] = $request->input('type','collection');
            $query_string['type'] = $request->input('type','collection');
        }

        $product = $this->moduleRepository->getsWith(['tags','collections','plans'=>function($query){
            $query->where('active',1);
        }],$where,['status'=>'DESC','updated_at'=>'DESC']);
        $data = [
            'actionName'=>__FUNCTION__,
            'module_name'=> $this->moduleName,
            'query_string' => $query_string,
            'actions'=>['assigned','sorted','new'],
            'tabs'=>['type'=>['collection','single'], 'status'=>[1,0]],
            'table_data' => $product,
            'table_head' =>['id','name','type','model','plans','status'],
            'table_formatter' =>['plans', 'status'],
        ];
        return view('admin.list',$data);
    }

    public function create()
    {
        $data = [
            'actionName'=>__FUNCTION__,
            'module_name'=> $this->moduleName,
            'tags'=>$this->tagRepository->gets(),
            'singles'=>$this->moduleRepository->getsWith([],['type'=>'single']),
            'collections'=>$this->moduleRepository->getsWith([],['type'=>'collection'],['created_at'=>'DESC']),
            'data'=>null,
        ];
        return view('admin.form',$data);
    }

    public function edit($id)
    {
        $product =  $this->moduleRepository->getWith($id,['tags','collections']);
        $data = [
            'actionName'=>__FUNCTION__,
            'module_name'=> $this->moduleName,
            'tags'=>$this->tagRepository->gets(),
            'collections'=>$this->moduleRepository->getsWith([],['type'=>'collection']),
            'singles'=>$this->moduleRepository->getsWith([],['type'=>'single'])->whereNotIn('id', array_merge( [$id], $product ? $product->collections->map(function($item, $key){return $item->id;})->toArray(): [])),
            'data' => $product,
        ];
        return view('admin.form',$data);
    }
    public function store(Request $request)
    {
        $http = new \GuzzleHttp\Client;
        $response = $http->request('post',url('/'.str_plural($this->moduleName)),[
                'headers'=>[
                    'Accept' => 'application/json',
                    'Authorization' => 'Bearer '.$this->token['access_token'],
                ],
                'form_params' => $request->all(),
            ]);
        $response_product = json_decode((string) $response->getBody(), true);
        
        if($response_product['status']=='success'){
            $requet_avatars = $request->only('avatars');
            $avatars = $requet_avatars['avatars'];
            foreach ($avatars as $key => $avatar) {
                if(!array_key_exists('avatar',$avatar)){
                    continue;
                }
                $file_path = $avatar['avatar']->getPathname();
                $file_mime = $avatar['avatar']->getmimeType();
                $file_org  = $avatar['avatar']->getClientOriginalName();
                $http = new \GuzzleHttp\Client;
                $response = $http->request('post',url('/'.str_plural($this->moduleName)).'/avatar/'.$response_product['data']['id'],[
                        'headers'=>[
                            'Accept' => 'application/json',
                            'Authorization' => 'Bearer '.$this->token['access_token'],
                        ],
                        'multipart' => [
                            [
                                'name'     => 'avatar',
                                'filename' => $file_org,
                                'Mime-Type'=> $file_mime,
                                'contents' => fopen( $file_path, 'r' ),
                            ],
                            [
                                'name'     => 'avatar_type',
                                'contents' => $avatar['avatar_type'],
                            ],
                        ],
                    ]);

                $response_avatar = json_decode((string) $response->getBody(), true);
            }
        }
        return $this->adminResponse($request,$response_product);
    }

    public function update(Request $request, $id)
    {
        $http = new \GuzzleHttp\Client;
        $response = $http->request('put',url('/'.str_plural($this->moduleName).'/'.$id),[
                'headers'=>[
                    'Accept' => 'application/json',
                    'Authorization' => 'Bearer '.$this->token['access_token'],
                ],
                'form_params' => $request->all(),
            ]);
        $response_product = json_decode((string) $response->getBody(), true);
        
        if($response_product['status']=='success'){
            $requet_avatars = $request->only('avatars');

            $avatars = $requet_avatars['avatars'];
            foreach ($avatars as $key => $avatar) {
                if(!array_key_exists('avatar',$avatar)){
                    continue;
                }
                $file_path = $avatar['avatar']->getPathname();
                $file_mime = $avatar['avatar']->getmimeType();
                $file_org  = $avatar['avatar']->getClientOriginalName();
                $http = new \GuzzleHttp\Client;
                $response = $http->request('post',url('/'.str_plural($this->moduleName)).'/avatar/'.$response_product['data']['id'],[
                        'headers'=>[
                            'Accept' => 'application/json',
                            'Authorization' => 'Bearer '.$this->token['access_token'],
                        ],
                        'multipart' => [
                            [
                                'name'     => 'avatar',
                                'filename' => $file_org,
                                'Mime-Type'=> $file_mime,
                                'contents' => fopen( $file_path, 'r' ),
                            ],
                            [
                                'name'     => 'avatar_type',
                                'contents' => $avatar['avatar_type'],
                            ],
                        ],
                    ]);

                $response_avatar = json_decode((string) $response->getBody(), true);
            }
            if($request->input('deleted')){
                
                $response = $http->request('delete',url('/'.str_plural($this->moduleName)).'/avatar/'.$response_product['data']['id'],[
                        'headers'=>[
                            'Accept' => 'application/json',
                            'Authorization' => 'Bearer '.$this->token['access_token'],
                        ],
                        'form_params' => $request->only('deleted'),
                    ]);

                $response_avatar = json_decode((string) $response->getBody(), true);
            }
        }
        return $this->adminResponse($request,$response_product);
    }
    public function assignedView(UserRepository $userRepository)
    {
        $products = $this->moduleRepository->getsWith([],[],['status'=>'DESC','updated_at'=>'DESC']);
        $users = $userRepository->getsWith(['profile']);
        $data = [
            'actionName'=>__FUNCTION__,
            'module_name'=> $this->moduleName,
            'products' =>$products,
            'users' => $users,
        ];
        return view('admin.assigned', $data);
    }
    public function assigned(Request $request)
    {   
        $send_email = $request->input('send_email',0) ? 1 : 0;
        $products = $request->input('products', []);
        $quantity = $request->input('quantity', 1);
        $user_ids = $request->input('users', []);
        $notificationMessage = new \stdClass();
        $notificationMessage->send_notice = 1;
        $notificationMessage->send_email = $send_email;
        $notificationMessage->type= 'ProductReceive';
                
        foreach ($products as $key => $product) {
            $products[$key]['quantity'] = $quantity;
        }

        foreach ($user_ids as $key => $user_id) {
            $result = $this->addProducts($request, $user_id, $products);
            if($result['status']=='success'){
                $user = $this->userRepository->get($user_id);
                $user->notify(new ProductReceive($user, $quantity , $products, $notificationMessage));
            }
        }
        return redirect('admin/products');
    }
    public function sortedView()
    {
        $products = $this->moduleRepository->getsWith([],['status'=>1], ['sort'=>'ASC']);
        $data = [
            'actionName'=>__FUNCTION__,
            'module_name'=> $this->moduleName,
            'products' =>$products,
        ];
        return view('admin.sorted', $data);
    }
    public function sorted(Request $request)
    {   
        $product_ids = $request->input('products', []);
        foreach ($product_ids as $key => $product_id) {
           $this->moduleRepository->update($product_id, ['sort'=>$key]);
        }
        return redirect('admin/products');
    }
    private function addProducts($request, $user_id, $products){
        $request->request->add([
            'products' => $products,
            'user_id' => $user_id,
        ]);
        $tokenRequest = $request->create(
            url('/user/products'),
            'post'
        );
        $tokenRequest->headers->set('Accept','application/json');
        $tokenRequest->headers->set('Authorization','Bearer '.(isset($this->token['access_token'])? $this->token['access_token']:''));

        $instance = Route::dispatch($tokenRequest);
        return json_decode($instance->getContent(), true);
    }

    public function export(Request $request)
    {
        $where['id.in'] = $request->ids;
        $data = $this->moduleRepository->getsWith(['tags','collections','plans'=>function($query){
            $query->where('active',1);
        }],$where,['status'=>'DESC','updated_at'=>'DESC'])->toArray();
        $sheet = [];
        $product = ['編號' => null, '名稱' => null, '類型' => null, '對應API' => null, '購買方案' => null, '狀態' => null];
        foreach ($data as $row) {
            foreach ($row as $key => $value) {
                switch ($key) {
                    case "id":
                        $product['編號'] = $value;
                        break;
                    case "name":
                        $product['名稱'] = $value;
                        break;
                    case "type":
                        $product['類型'] = $value;
                        break;
                    case "model":
                        $product['對應API'] = $value;
                        break;
                    case "plans":
                        if (!empty($row['plans'])) 
                            $product['購買方案'] = Lang::get('product.admin.expiration_'. $row['plans'][0]['expiration']) . $row['plans'][0]['price'] . Lang::get('product.admin.unit');
                        break;
                    case "status":
                        $product['狀態'] = Lang::get('product.admin.status_'.$value);
                        break;
            
                }
            }
            array_push($sheet, $product);
            $product = array_fill_keys(array_keys($product), null);
        }
        $this->tableExport($sheet);
    }

}
