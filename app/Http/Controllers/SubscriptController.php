<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Validator;

class SubscriptController extends Controller
{	

    protected $userRepository;
    public function __construct(UserRepository $userRepository)
    {
       $this->userRepository = $userRepository;
    }

    public function index()
    {
        //
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {

    }

    public function show(Request $request)
    {
       
    }

    public function edit($id)
    {
      
    }
    public function cancel(Request $request)
    {
        $user = $request->user();

        $data = ['subscription'=>0];
        $user->update($data);
        
        return $this->successResponse($data);
    }
     public function subscript(Request $request)
    {
        $user = $request->user();

        $data = ['subscription'=>1];
        $user->update($data);
        
        return $this->successResponse($data);
    }
    public function update(Request $request, $id)
    {
        
    }

    public function destroy($id)
    {
        //
    }
}
