<?php

namespace App\Http\Controllers;

use App\Repositories\UserRecordRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserRecordController extends Controller
{	

    protected $userRecordRepository;
    public function __construct(UserRecordRepository $userRecordRepository)
    {
        $this->userRecordRepository = $userRecordRepository;
    }

    public function store(Request $request)
    {
        $user = $request->user();
        $user_id = 0;
        if($user){
            $user_id = $user->id;
        }

        $stock_code = $request->input('stock_code');
        $created_at = date('Y-m-d H:i:s');

        $this->userRecordRepository->create(['stock_code'=>$stock_code, 'ip'=>$request->ip(),'user_id'=>$user_id, 'created_at'=>$created_at]);
        return $this->successResponse(['recorded'=>1, 'stock_code'=>$stock_code, 'ip'=>$request->ip()]);
    }
}
