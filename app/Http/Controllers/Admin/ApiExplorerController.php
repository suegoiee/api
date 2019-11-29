<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
class ApiExplorerController extends Controller
{
    public function index(){
    	return view('home');
    }
}
