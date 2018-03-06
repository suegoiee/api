<?php
namespace App\Repositories;
use App\Article;

class ArticleRepository extends Repository
{
	public function __construct(Article $article){
		$this->model = $article;
	}
	
	public function getsWithByStatus($with=[]){
		return $this->model->with($with)->where('status',1)->get();
	}

	public function getWithByStatus($id, $with=[]){
		return $this->model->with($with)->where('status',1)->where('id',$id)->first();
	}
}