<?php
namespace App\Repositories;
use App\Product;

class ProductRepository extends Repository
{
	public function __construct(Product $product){
		$this->model = $product;
	}
	public function isOwner($user_id,$id){
		return (boolean)$this->model->where('user_id',$user_id)->where('id',$id)->count();
	}
	public function getsWithByStatus($with=[]){
		return $this->model->with($with)->where('status',1)->orderBy('sort')->get();
	}
	public function getWithByStatus($id, $with=[]){
		return $this->model->with($with)->where('status',1)->where('id',$id)->first();
	}
}