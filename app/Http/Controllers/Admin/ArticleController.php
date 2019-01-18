<?php
namespace App\Http\Controllers\Admin;
use App\Repositories\TagRepository;
use App\Repositories\ArticleRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Lang;

class ArticleController extends AdminController
{	
    protected $tagRepository;

    public function __construct(Request $request, ArticleRepository $articleRepository, TagRepository $tagRepository)
    {
        parent::__construct($request);
        $this->moduleName = 'article';
        $this->moduleRepository = $articleRepository;
        $this->tagRepository = $tagRepository;
        //$this->token = $this->clientCredentialsGrantToken();
    }

    public function index()
    {
        $article = $this->moduleRepository->getsWith(['tags'],[],['top'=>'DESC','status'=>'DESC','posted_at'=>'DESC']);
        $data = [
            'actionName'=>__FUNCTION__,
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
            'actionName'=>__FUNCTION__,
            'module_name'=> $this->moduleName,
            'tags'=>$this->tagRepository->gets(),
            'data'=>null,
        ];
        return view('admin.form',$data);
    }

    public function edit($id)
    {

        $data = [
            'actionName'=>__FUNCTION__,
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

    public function export(Request $request)
    {
        $where['id.in'] = $request->ids;
        $data = $this->moduleRepository->getsWith(['tags'], $where, ['top'=>'DESC','status'=>'DESC','posted_at'=>'DESC'])->toArray();
        $sheet = [];
        $article = ['代號' => null, '標題' => null, '狀態' => null, '發布時間' => null];
        foreach ($data as $row) {
            foreach ($row as $key => $value) {
                switch ($key) {
                    case "id":
                        $article['代號'] = $value;
                        break;
                    case "title":
                        $article['標題'] = $value;
                        break;
                    case "status":
                        $article['狀態'] = Lang::get('article.admin.status_'.$value); 
                        break;
                    case "created_at":
                        $article['發布時間'] = $value;
                        break;
                }
            }
            array_push($sheet, $article);
            $article = array_fill_keys(array_keys($article), null);
        }
        $this->tableExport($sheet);
    }
}
