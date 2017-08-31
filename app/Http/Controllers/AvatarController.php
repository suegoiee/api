<?php

namespace App\Http\Controllers;

use Route;
use App\User;
use App\Traits\ImageStorage;
use App\Repositories\AvatarRepository;
use App\Repositories\ProductRepository;
use App\Repositories\LaboratoryRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AvatarController extends Controller
{	
    use ImageStorage;

    protected $avatarRepository;
    protected $productRepository;
    protected $laboratoryRepository;

    public function __construct(
        AvatarRepository $avatarRepository, 
        ProductRepository $productRepository,
        LaboratoryRepository $laboratoryRepository)
    {
	   $this->avatarRepository = $avatarRepository;
       $this->productRepository = $productRepository;
       $this->laboratoryRepository = $laboratoryRepository;
    }

    public function index()
    {
        //
    }

    public function create()
    {
        //
    }

    public function store(Request $request,$module_id)
    {
        $moduleRepository = $this->moduleRepository($request,$module_id);
        if(!$moduleRepository){
            return $this->failedResponse(['message'=>trans('auth.permission_deined')]);
        }
        $validator = $this->avatarValidator($request->all());
        if($validator->fails()){
            return $this->failedResponse($validator->errors()->all());
        }

        $data = ['path' => $this->storeAvatar($request->file('avatar'), $module_id,$this->getModuleName()),'type'=>$request->input('avatar_type','normal')];
        $moduleRepository->avatar()->create($data);
        return $this->successResponse($data);
    }

    public function show(Request $request, $module_id)
    {
        $moduleRepository = $this->moduleRepository($request,$module_id, false);

        $avatar = $moduleRepository->avatar()->orderBy('created_at', 'desc')->first();
        return $this->successResponse($avatar);
    }

    public function edit($id)
    {
      
    }

    public function update(Request $request, $module_id)
    {
        $moduleRepository = $this->moduleRepository($request,$module_id);
        if(!$moduleRepository){
            return $this->failedResponse(['message'=>trans('auth.permission_deined')]);
        }
        $validator = $this->avatarValidator($request->all());
        if($validator->fails()){
            return $this->failedResponse($validator->errors()->all());
        }

        $data = ['path' => $this->storeAvatar($request->file('avatar'), $module_id, $this->getModuleName()),'type'=>$request->input('avatar_type','normal')];
        $avatar = $moduleRepository->avatar()->orderBy('created_at', 'desc')->first();
        $this->avatarRepository->update($avatar->id,$data);
        $this->destroyAvatar($avatar->path);
        return $this->successResponse($data);
    }

    public function destroy(Request $request, $module_id)
    {
        $moduleRepository = $this->moduleRepository($request,$module_id);
        if(!$moduleRepository){
            return $this->failedResponse(['message'=>trans('auth.permission_deined')]);
        }
        $avatars = $moduleRepository->avatar()->get();
        $moduleRepository->avatar()->delete();
        $deleted = $avatars->map(function($item,$key){return $item->path;})->all();
        $this->destroyAvatar($deleted);
        return $this->successResponse($deleted);
    }

    protected function avatarValidator(array $data)
    {
        return Validator::make($data, [
            'avatar' => 'required|image',
        ]);
    }

    protected function moduleRepository($request, $module_id, $validation = true)
    {
        $module_name = Route::currentRouteName();
        if(preg_match("/^user/i", $module_name)){
            $user = $request->user();
            if($user->id != $module_id && $validation){
                return false;
            }
            return User::find($module_id);
        }

        if(preg_match("/^product/i", $module_name)){

            return $this->productRepository->get($module_id);
        }
        if(preg_match("/^laboratories/i", $module_name)){

            return $this->laboratoryRepository->get($module_id);
        }
        return false;
    }
    protected function getModuleName()
    {
        $current_route_name = Route::currentRouteName();
        $module_name = explode('.', $current_route_name);
        if(count($module_name)>0){
            return $module_name[0];
        }
        return 'undefine';
    }
}
