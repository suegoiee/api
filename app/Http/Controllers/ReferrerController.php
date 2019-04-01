<?php

namespace App\Http\Controllers;

use App\Traits\OauthToken;
use App\Repositories\ReferrerRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Route;
class ReferrerController extends Controller
{	
    use OauthToken;
    protected $referrerRepository;
    public function __construct(ReferrerRepository $referrerRepository)
    {
        $this->referrerRepository = $referrerRepository;
    }
    public function check(Request $request)
    {
        $code = $request->input('code');
        $referrer = $this->referrerRepository->whereBy(['code'=>$code])->toGet();
        if($referrer){
            return $this->successResponse(["code"=>$referrer->code, "name"=>$referrer->name, "is_exists"=>1]);
        }
        return $this->successResponse(["code"=>$code, "name"=>"", "is_exists"=>0]);
    }
}
