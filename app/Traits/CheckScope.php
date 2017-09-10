<?php
namespace App\Traits;
use Illuminate\Http\Request;
trait CheckScope
{
	protected function tokenCan(Request $request,$scope){
		return $request->user()->tokenCan($scope);
	}
}

