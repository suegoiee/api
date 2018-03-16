<?php
namespace App\Http\Controllers\Admin;
use App\Repositories\TagRepository;
use App\Repositories\ArticleRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ArticleController extends AdminController
{	
    protected $tagRepository;

    public function __construct(ArticleRepository $articleRepository, TagRepository $tagRepository)
    {
        $this->moduleName = 'article';
        $this->moduleRepository = $articleRepository;
        $this->tagRepository = $tagRepository;

        $this->token = $this->clientCredentialsGrantToken();
    }

    public function index()
    {
        $article = $this->moduleRepository->getsWith(['tags'],[],['top'=>'DESC','status'=>'DESC','posted_at'=>'DESC']);
        $data = [
            'module_name'=> $this->moduleName,
            'actions'=>['new'],
            'table_data' => $article,
            'table_head' =>['id','title','status','posted_at'],
            'table_formatter' =>['title','status'],
        ];
        return view('admin.list',$data);
    }

    public function create()
    {
        $data = [
            'module_name'=> $this->moduleName,
            'tags'=>$this->tagRepository->gets(),
            'data'=>null,
        ];
        return view('admin.form',$data);
    }

    public function edit($id)
    {

        $data = [
            'module_name'=> $this->moduleName,
            'tags'=>$this->tagRepository->gets(),
            'data' => $this->moduleRepository->getWith($id,['tags',]),
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

        $response_article = json_decode((string) $response->getBody(), true);
        
        return $this->adminResponse($request,$response_article);
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

        $response_article = json_decode((string) $response->getBody(), true);
        
        return $this->adminResponse($request,$response_article);
    }
}
