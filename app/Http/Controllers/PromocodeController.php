<?php

namespace App\Http\Controllers;

use App\Traits\OauthToken;
use App\Repositories\PromocodeRepository;
use App\Repositories\UserRepository;
use App\Notifications\PromocodeReceive;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PromocodeController extends Controller
{	
    use OauthToken;
    protected $promocodeRepository;
    protected $userRepository;
    public function __construct(PromocodeRepository $promocodeRepository, UserRepository $userRepository)
    {
        $this->promocodeRepository = $promocodeRepository;
        $this->userRepository = $userRepository;
    }

    public function index(Request $request)
    {
        $promocodes = $this->promocodeRepository->getsWith(['user'],[],['updated_at'=>'DESC'])->get();
        return $this->successResponse($promocodes);
    }
    public function list(Request $request)
    {
        $user = $request->user();
        $promocodes = $user->promocodes()->with(['products'=>function($query){
            $query->select(['id','name','info_short','price']);
        }])->get()->makeHidden(['user','user_name','created_at','updated_at','order_id','user_id']);
        $promocode_unassigned = $this->promocodeRepository->getsWith(['user','products'=>function($query){
            $query->select(['id','name','info_short','price']);
        }],['type'=>0],['updated_at'=>'DESC'])->makeHidden(['user','user_name','created_at','updated_at','order_id','user_id']);
        $promocodes = $promocodes->merge($promocode_unassigned);
        return $this->successResponse($promocodes);
    }
    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $validator = $this->promocodeValidator($request->all());
        if($validator->fails()){
            return $this->validateErrorResponse($validator->errors()->all());
        }

        $request_data = $request->only(['name', 'code', 'offer', 'deadline', 'user_id', 'used_at', 'type']);
        $request_data['specific'] = $request->input('specific',0);
        if($request_data['type']=='0'){
            $request_data['user_id']=0;
            $request_data['send']=0;
        }
        $request_data['used_at'] = $request_data['used_at'] ? date('Y-m-d H:i:s') : null;
        $promocode = $this->promocodeRepository->create($request_data);
        if($request_data['specific']==1){
            $products = $request->input('products',[]);
            $promocode->products()->sync($products);
        }else{
            $promocode->products()->sync([]);
        }
        if($request_data['type']=='1'){
            if($request_data['user_id']!=0 && $promocode->send == 0){
                $user = $this->userRepository->get($request_data['user_id']);
                $user->notify(new PromocodeReceive($user, [$promocode->id]));
                $promocode->update(['send'=>1]);
            }
        }
        
        return $this->successResponse($promocode?$promocode:[]);
    }

    public function show(Request $request, $id = 0)
    {
        $user = $request->user();
        if($id!=0){
            if(!($this->promocodeRepository->isOwner($user->id,$id)) && !$user->tokenCan('promocode')){
                return $this->failedResponse(['message'=>[trans('auth.permission_denied')]]);
            }

            $promocode = $this->promocodeRepository->get($id);
        }else{
            $code = $request->input('code','');
            if($this->promocodeRepository->check($user->id, $code)){
                $promocode = $this->promocodeRepository->getBy(['code'=>$code]);
            }else{
                $promocode = false;
            }
        }
        return $this->successResponse($promocode ? $promocode->makeHidden(['user','user_name','created_at','updated_at','order_id','user_id']) : []);
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

        $request_data = $request->only(['name', 'code', 'offer', 'deadline', 'user_id', 'used_at', 'type']);
        $request_data['specific'] = $request->input('specific',0);
        if($request_data['type']=='0'){
            $request_data['user_id']=0;
            $request_data['send']=0;
        }
        $old_promocode = $this->promocodeRepository->get($id);
        $request_data['used_at'] = !$old_promocode->used_at && $request_data['used_at'] ? date('Y-m-d H:i:s') : null;
        $promocode = $this->promocodeRepository->update($id,$request_data);
        if($request_data['specific']==1){
            $products = $request->input('products',[]);
            $promocode->products()->sync($products);
        }else{
            $promocode->products()->sync([]);
        }
        if($request_data['type']=='1'){
            if($request_data['user_id']!=0 && $promocode->send == 0){
                $user = $this->userRepository->get($request_data['user_id']);
                $user->notify(new PromocodeReceive($user, [$promocode->id]));
                $promocode->update(['send'=>1]);
            }
        }
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
