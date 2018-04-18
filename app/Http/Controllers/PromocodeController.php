<?php

namespace App\Http\Controllers;

use App\Traits\OauthToken;
use App\Repositories\PromocodeRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PromocodeController extends Controller
{	
    use OauthToken;
    protected $promocodeRepository;
    public function __construct(PromocodeRepository $promocodeRepository)
    {
	   $this->promocodeRepository = $promocodeRepository;
    }

    public function index(Request $request)
    {
        $promocodes = $this->promocodeRepository->getsWith(['user'],[],['updated_at'=>'DESC'])->get();
        return $this->successResponse($promocodes);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $user = $request->user();
        $validator = $this->promocodeValidator($request->all());
        if($validator->fails()){
            return $this->validateErrorResponse($validator->errors()->all());
        }

        $request_data = $request->only(['name', 'code', 'offer', 'deadline', 'user_id', 'used_at']);
        $promocode = $this->promocodeRepository->create($request_data);
        
        return $this->successResponse($promocode?$promocode:[]);
    }

    public function show(Request $request, $id)
    {
        $user = $request->user();
        if(!($this->promocodeRepository->isOwner($user->id,$id)) && !$user->tokenCan('promocode')){
            return $this->failedResponse(['message'=>[trans('auth.permission_denied')]]);
        }

        $promocode = $this->promocodeRepository->find($id);

        return $this->successResponse($promocode?$promocode:[]);
    }

    public function edit($id)
    {
      
    }

    public function update(Request $request, $id)
    {
        $validator = $this->promocodeValidator($request->all(), $id);

        if($validator->fails()){
            return $this->validateErrorResponse($validator->errors()->all());
        }

        $request_data = $request->only(['name', 'code', 'offer', 'deadline', 'user_id', 'used_at']);
        $request_data['used_at'] = $request_data['used_at'] ? date('Y-m-d H:i:s') : null;
        $promocode = $this->promocodeRepository->update($id,$request_data);

        return $this->successResponse($promocode?$promocode:[]);
    }

    public function destroy(Request $request, $id)
    {
        $user = $request->user();
        if(!($this->promocodeRepository->isOwner($user->id,$id)) && !$user->tokenCan('promocode')){
            return $this->failedResponse(['message'=>[trans('auth.permission_denied')]]);
        }
        $promocode = $this->promocodeRepository->get($id);
        if($promocode){
            $this->promocodeRepository->delete($id);  
            return $this->successResponse(['id'=>$id]);
        }
        return $this->failedResponse(['message'=>[trans('promocode.delete_error')]]);
    }

    protected function promocodeValidator(array $data, $id = false)
    {
        return Validator::make($data, [
            //'user_id' => 'required|exists:users,id',
            'name' => 'max:255',
            'code'=>'required|unique:promocodes,code'.($id ? ','.$id : ''),
            'offer'=>'required|numeric',
        ]);        
    }
}
