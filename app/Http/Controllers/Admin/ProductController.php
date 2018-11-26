<?php
namespace App\Http\Controllers\Admin;
use App\Notifications\ProductReceive;
use App\Repositories\TagRepository;
use App\Repositories\ProductRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
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

        //$this->token = $this->clientCredentialsGrantToken();
    }

    public function index()
    {
        $product = $this->moduleRepository->getsWith(['tags','collections','plans'=>function($query){
            $query->where('active',1);
        }],[],['status'=>'DESC','updated_at'=>'DESC']);
        $data = [
            'actionName'=>__FUNCTION__,
            'module_name'=> $this->moduleName,
            'actions'=>['assigned','sorted','new'],
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
            'collections'=>$this->moduleRepository->getsWith([],['type'=>'single']),
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
            'collections'=>$this->moduleRepository->getsWith([],['type'=>'single'])->whereNotIn('id', array_merge( [$id], $product ? $product->collections->map(function($item, $key){return $item->id;})->toArray(): [])),
            'data' => $product,
        ];
        return view('admin.form',$data);
    }
    public function store(Request $request)
    {
        $request->request->add($request->all());
        $request->headers->set('Accept','application/json');
        $request->headers->set('Authorization','Bearer '.isset($this->token['access_token'])? $this->token['access_token']:'');
        $tokenRequest = $request->create(
            env('APP_URL').'/'.str_plural($this->moduleName),
            'post'
        );
        $instance = Route::dispatch($tokenRequest);

        $response_product= json_decode($instance->getContent(), true);

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
        $request->request->add($request->all());
        $request->headers->set('Accept','application/json');
        $request->headers->set('Authorization','Bearer '.isset($this->token['access_token'])? $this->token['access_token']:'');
        $tokenRequest = $request->create(
            env('APP_URL').'/'.str_plural($this->moduleName).'/'.$id,
            'put'
        );
        $instance = Route::dispatch($tokenRequest);

        $response_product= json_decode($instance->getContent(), true);

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
                
                $request->request->add($request->only('deleted'));
                $request->headers->set('Accept','application/json');
                $request->headers->set('Authorization','Bearer '.isset($this->token['access_token'])? $this->token['access_token']:'');
                $tokenRequest = $request->create(
                    env('APP_URL').'/'.str_plural($this->moduleName).'/avatar/'.$response_product['data']['id'],
                    'delete'
                );
                $instance = Route::dispatch($tokenRequest);

                $response_avatar = json_decode($instance->getContent(), true);
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
        $send_email = $request->input('send_email',0) ? true : false;
        $product_ids = $request->input('products', []);
        $user_ids = $request->input('users', []);
        foreach ($user_ids as $key => $user_id) {
            $result = $this->addProducts($user_id, $product_ids);
            if($result['status']=='success'){
                $user = $this->userRepository->get($user_id);
                $user->notify(new ProductReceive($user, $product_ids, $send_email));
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
    private function addProducts($user_id, $products){
        $tokenRequest = Request::create(
            env('APP_URL').'/user/products',
            'post'
        );
        $tokenRequest->request->add([
            'products' => $products,
            'user_id' => $user_id,
        ]);
        $tokenRequest->headers->set('Accept','application/json');
        $tokenRequest->headers->set('Authorization','Bearer '.isset($this->token['access_token'])? $this->token['access_token']:'');
        $instance = Route::dispatch($tokenRequest);

        return  json_decode($instance->getContent(), true);
    }
}
