<?php

namespace App\Http\Controllers;

use App\Repositories\ArticleRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ArticleController extends Controller
{	
    protected $articleRepository;

    public function __construct(ArticleRepository $articleRepository)
    {
	   $this->articleRepository = $articleRepository;
    }

    public function index()
    {
        $article = $this->articleRepository->getsWith(['tags','collections','faqs']);

        return $this->successResponse($article?$article:[]);
    }
    public function publishList()
    {
        $article = $this->articleRepository->getsWithByStatus(['tags'=>function($query){$query->select('name');}])->makeHidden(['status', 'created_at', 'updated_at', 'deleted_at']);

        return $this->successResponse($article?$article:[]);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $validator = $this->articleValidator($request->all());
        if($validator->fails()){
            return $this->validateErrorResponse($validator->errors()->all());
        }

        $request_data = $request->only(['title','content','top','status','posted_at']);
        $request_data['posted_at'] = $request_data['posted_at'] != null ? date('Y-m-d H:i:s', strtotime($request_data['posted_at'])):date('Y-m-d H:i:s');
        $request_data['top'] = $request_data['top']!=null? '1':'0';
        $article = $this->articleRepository->create($request_data);

        $tags = $request->input('tags',[]);
        $article->tags()->attach($tags);

        return $this->successResponse($article?$article:[]);
    }

    public function show(Request $request, $id)
    {
        
        $article = $this->articleRepository->getWith($id,['tags']);

        return $this->successResponse($article?$article:[]);
    }
    public function onPublish(Request $request, $id)
    {
        
        $article = $this->articleRepository->getWithByStatus($id, ['tags']);

        return $this->successResponse($article?$article:[]);
    }

    public function edit($id)
    {
      
    }

    public function update(Request $request, $id)
    {
        $validator = $this->articleUpdateValidator($request->all());
        if($validator->fails()){
            return $this->validateErrorResponse($validator->errors()->all());
        }

        $request_data = $request->only(['title','content','top','status','posted_at']);
        $request_data['posted_at'] = $request_data['posted_at'] != null ? date('Y-m-d H:i:s', strtotime($request_data['posted_at'])):date('Y-m-d H:i:s');
        $request_data['top'] = $request_data['top']!=null? '1':'0';
        $data = array_filter($request_data, function($item){return $item!=null;});

        $article = $this->articleRepository->update($id,$data);

        $tags = $request->input('tags',[]);
        $article->tags()->sync($tags);

        return $this->successResponse($article?$article:[]);
    }

    public function destroy(Request $request, $id)
    {
        $this->articleRepository->delete($id);
        return $this->successResponse(['id'=>$id]);

    }

    protected function articleValidator(array $data)
    {
        return Validator::make($data, [
            'title' => 'required|max:255',
            'content' => 'string',
        ]);        
    }

    protected function articleUpdateValidator(array $data)
    {
        return Validator::make($data, [
            'title' => 'required|max:255',
            'content' => 'string',
        ]);        
    }
}
