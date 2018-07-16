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
    public function __construct(ProductRepository $productRepository, TagRepository $tagRepository, UserRepository $userRepository)
    {
        $this->moduleName='product';
        $this->moduleRepository = $productRepository;
        $this->tagRepository = $tagRepository;
        $this->userRepository = $userRepository;

        $this->token = $this->clientCredentialsGrantToken();
    }

    public function index()
    {
        $product = $this->moduleRepository->getsWith(['tags','collections'],[],['status'=>'DESC','updated_at'=>'DESC']);
        $data = [
            'module_name'=> $this->moduleName,
            'actions'=>['assigned','sorted','new'],
            'table_data' => $product,
            'table_head' =>['id','name','type','model','price','status'],
            'table_formatter' =>['status'],
        ];
        return view('admin.list',$data);
    }

    public function create()
    {
        $data = [
            'module_name'=> $this->moduleName,
            'tags'=>$this->tagRepository->gets(),
            'collections'=>$this->moduleRepository->getsWith([],['type'=>'single']),
            'data'=>null,
        ];
        return view('admin.form',$data);
    }

    public function edit($id)
    {

        $data = [
            'module_name'=> $this->moduleName,
            'tags'=>$this->tagRepository->gets(),
            'collections'=>$this->moduleRepository->getsWith([],['type'=>'single'])->whereNotIn('id',[$id]),
            'data' => $this->moduleRepository->getWith($id,['tags','collections']),
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
        $products = $this->moduleRepository->getsWith([],[],['status'=>'DESC']);
        $users = $userRepository->gets();
        $data = [
            'module_name'=> $this->moduleName,
            'products' =>$products,
            'users' => $users,
        ];
        return view('admin.assigned', $data);
    }
    public function assigned(Request $request)
    {   
        $product_ids = $request->input('products', []);
        $user_ids = $request->input('users', []);
        foreach ($user_ids as $key => $user_id) {
            $result = $this->addProducts($user_id, $product_ids);
            if($result['status']=='success'){
                $user = $this->userRepository->get($user_id);
                $user->notify(new ProductReceive($user, $product_ids));
            }
        }
        return redirect('admin/products');
    }
    public function sortedView()
    {
        $products = $this->moduleRepository->getsWith([],['status'=>1], ['sort'=>'ASC']);
        $data = [
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
        $token = $this->token;
        $http = new \GuzzleHttp\Client;
        $response = $http->request('post',url('/user/products'),[
                'headers'=>[
                    'Accept' => 'application/json',
                    'Authorization' => 'Bearer '.$token['access_token'],
                ],
                'form_params' => [
                    'products' => $products,
                    'user_id' => $user_id,
                ],
            ]);
        return json_decode((string) $response->getBody(), true);
    }
}
