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

    public function show(Request $request, $id)
    {
        $user = $request->user();
        if(!($this->profileRepository->isOwner($user->id,$id))){
            return $this->failedResponse(['message'=>trans('auth.permission_deined')]);
        }

        $profile = $this->profileRepository->get($id);
        return $this->successResponse($profile?$profile:[]);
    }

    public function edit($id)
    {
      
    }

    public function update(Request $request, $id)
    {
        $user = $request->user();
        if(!($this->profileRepository->isOwner($user->id,$id))){
            return $this->failedResponse(['message'=>trans('auth.permission_deined')]);
        }

        $validator = $this->profileValidator($request->all());
        if($validator->fails()){
            return $this->failedResponse($validator->errors()->all());
        }

        $request_data = $request->only(['nike_name','name','sex','address','birthday']);

        $data = array_filter($request_data, function($item){return $item!=null;});

        $profile = $this->profileRepository->update($id,$data);

        return $this->successResponse($profile?$profile:[]);
    }

    public function destroy($id)
    {
        //
    }

    protected function profileValidator(array $data)
    {
        return Validator::make($data, [
            'nike_name' => 'max:255',
            'name' => 'max:255',
            'sex'=>'in:F,M',
            'address'=>'max:255',
            'birthday'=>'date',
        ]);        
    }
}
