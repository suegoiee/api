<?php

namespace App\Http\Controllers;

use App\Repositories\ProfileRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{	

    protected $profileRepository;

    public function __construct(ProfileRepository $profileRepository)
    {
	   $this->profileRepository = $profileRepository;
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

    public function update(Request $request)
    {
        $user = $request->user();

        $validator = $this->profileValidator($request->all());
        if($validator->fails()){
            return $this->validErrorResponse($validator->errors()->all());
        }

        $request_data = $request->only(['nickname','name','sex','address','birthday','phone','company_id','invoice_title']);

        $data = array_filter($request_data, function($item){return $item!=null;});

        $user->profile()->update($data);
        $profile = $user->profile;
        $profile->email =  $user->email;
        return $this->successResponse($profile?$profile:[]);
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
