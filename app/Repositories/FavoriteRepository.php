<?php
namespace App\Repositories;

use App\Favorite;

class FavoriteRepository extends Repository
{
	public function __construct(Favorite $favorite){
		$this->model = $favorite;
	}
}