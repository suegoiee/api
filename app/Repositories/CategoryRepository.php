<?php
namespace App\Repositories;
use App\Category;
use App\CategoryProduct;

class CategoryRepository extends Repository
{
	public function __construct(Category $categories){
		$this->model = $categories;
	}
}