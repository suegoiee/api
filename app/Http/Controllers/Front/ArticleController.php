<?php
namespace App\Http\Controllers\Front;
use App\Http\Controllers\Controller;
use App\Repositories\TagRepository;
use App\Repositories\ArticleRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
class ArticleController extends Controller
{	
    protected $tagRepository;

    public function __construct(ArticleRepository $articleRepository, TagRepository $tagRepository)
    {
        $this->moduleName = 'article';
        $this->moduleRepository = $articleRepository;
        $this->tagRepository = $tagRepository;
    }

    public function index($slug = 0)
    {
        $articles = $this->moduleRepository->getsWithPaginate(['tags'],['status'=>'1'],['top'=>'DESC','status'=>'DESC','posted_at'=>'DESC'], 15);
        $data_num = $this->moduleRepository->getsWith(['tags'],['status'=>'1'],['top'=>'DESC','status'=>'DESC','posted_at'=>'DESC'])->count();
        $data = [
            'articles' => $articles,
            'data_num' => $data_num,
            'tags'=>$this->tagRepository->gets(),
        ];
        return view('front.blog',$data);
    }

    public function show($slug)
    {
        $slug = $this->hasChineseStr($slug) ? urlencode(str_replace('+', ' ', $slug)) : $slug;
        $article = $this->moduleRepository->getBy(['slug'=> $slug, 'status'=>'1'],['tags']);
        $articles = $this->moduleRepository->getsWith(['tags'],['status'=>'1'],['top'=>'DESC','status'=>'DESC','posted_at'=>'DESC']);
        $data = [
            'data' => $article,
            'data_num' => $articles->count(),
            'tags'=>$this->tagRepository->gets(),
        ];
        return view('front.archives',$data);
    }
    private function hasChineseStr($str){
        return mb_strlen($str, mb_detect_encoding($str)) != strlen($str);
    }
}
