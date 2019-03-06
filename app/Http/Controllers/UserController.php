<?php

namespace App\Http\Controllers;

use App\Repositories\UserRepository;
use App\Repositories\ProfileRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{	

    protected $profileRepository;
    protected $userRepository;

    public function __construct(ProfileRepository $profileRepository, UserRepository $userRepository)
    {
	   $this->profileRepository = $profileRepository;
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
        $user = $request->user();
        $profile = $user->profile;
        $profile->email =  $user->email;
        return $this->successResponse($profile?$profile:[]);
    }

    public function edit($id)
    {
      
    }

    public function update(Request $request, $id)
    {
        $user = $this->userRepository->get($id);

        if(!$user){
            return $this->validErrorResponse(['The user is not exists']);
        }

        $validator = $this->profileValidator($request->all());
        if($validator->fails()){
            return $this->validErrorResponse($validator->errors()->all());
        }

        $request_data = $request->only(['nickname','name','sex','address','birthday','phone','company_id','invoice_title']);

        $data = array_filter($request_data, function($item){return $item!=null;});

        $user->profile()->update($data);
        $profile = $user->profile;

        $products = $request->input('products');
        foreach ($products as $key => $product) {
            $user->products()->updateExistingPivot($product['id'], [ 'deadline' => $product['deadline'] == '0' ? null : $product['deadline'] ]);
        }
        return $this->successResponse($user?$user:[]);
    }

    public function destroy($id)
    {
        //
    }

    protected function profileValidator(array $data)
    {
        return Validator::make($data, [
            'nickname' => 'max:255',
            'name' => 'max:255',
            'sex'=>'in:F,M',
            'address'=>'max:255',
            'birthday'=>'date',
        ]);        
    }
}
