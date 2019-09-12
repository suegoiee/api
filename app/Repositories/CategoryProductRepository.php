<?php
namespace App\Repositories;
use App\CategoryProduct;

class CategoryProductRepository extends Repository
{
	public function __construct(CategoryProduct $category_products){
		$this->model = $category_products;
	}
	
	public function deleteByCategoryid($id){
		CategoryProduct::where('category_id', '=', $id)->delete();
	}
}