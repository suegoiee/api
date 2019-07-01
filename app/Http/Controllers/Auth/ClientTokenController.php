<?php

namespace App\Http\Controllers\Auth;
use Carbon\Carbon;
use Hash;
use App\User;
use App\Traits\OauthToken;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ClientTokenController extends Controller
{
    use OauthToken;
    
    public function check(Request $request)
    {
        return $this->successResponse(['login'=>1]);
    }
}
