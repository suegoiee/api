<?php

namespace App\Http\Controllers\Analyst;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(){
    	return redirect('analyst/orders');
    	return view('analyst.home');
    }
}
