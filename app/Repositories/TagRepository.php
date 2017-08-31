<?php
namespace App\Repositories;
use App\Tag;

class TagRepository extends Repository
{
	public function __construct(Tag $tag){
		$this->model = $tag;
	}
}