<?php
namespace App\Http\Controllers\Admin;
use App\Repositories\TagRepository;
use App\Repositories\ProductRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
class ProductController extends AdminController
{	
    protected $tagRepository;

    public function __construct(ProductRepository $productRepository, TagRepository $tagRepository)
    {
        $this->moduleName='product';
        $this->moduleRepository = $productRepository;
        $this->tagRepository = $tagRepository;

        $this->token = $this->clientCredentialsGrantToken();
    }

    public function index()
    {
        $product = $this->moduleRepository->getsWith(['tags','collections']);
        $data = [
            'module_name'=> $this->moduleName,
            'actions'=>['new'],
            'table_data' => $product,
            'table_head' =>['id','name','type','model','status'],
            'table_formatter' =>['status'],
        ];
        return view('admin.list',$data);
    }

    public function create()
    {
        $data = [
            'module_name'=> $this->moduleName,
            'tags'=>$this->tagRepository->gets(),
            'collections'=>$this->moduleRepository->gets(),
            'data'=>null,
        ];
        return view('admin.form',$data);
    }

    public function edit($id)
    {

        $data = [
            'module_name'=> $this->moduleName,
            'tags'=>$this->tagRepository->gets(),
            'collections'=>$this->moduleRepository->gets(),
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
}
