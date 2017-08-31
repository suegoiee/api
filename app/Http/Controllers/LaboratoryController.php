<?php

namespace App\Http\Controllers;
use App\Repositories\LaboratoryRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LaboratoryController extends Controller
{	

    protected $laboratoryRepository;

    public function __construct(LaboratoryRepository $laboratoryRepository)
    {
	   $this->laboratoryRepository = $laboratoryRepository;
    }

    public function index(Request $request)
    {
        $user = $request->user();
        $laboratories = $user->laboratories()->with('avatar','products','products.avatar_small')->get();
        return $this->successResponse($laboratories);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $user = $request->user();
        $validator = $this->laboratoryValidator($request->all());
        if($validator->fails()){
            return $this->validateErrorResponse($validator->errors()->all());
        }

        $request_data = $request->only(['title','layout']);

        $laboratory = $user->laboratories()->create($request_data);

        $products = $request->input('products');
        $laboratory->products()->syncWithoutDetaching($products);

        return $this->successResponse($laboratory?$laboratory:[]);
    }

    public function show(Request $request, $id)
    {
        $user = $request->user();
        if(!($this->laboratoryRepository->isOwner($user->id,$id))){
            return $this->failedResponse(['message'=>trans('auth.permission_deined')]);
        }

        $laboratory = $user->laboratories()->with('avatar','products','products.avatar_small')->find();

        return $this->successResponse($laboratory?$laboratory:[]);
    }

    public function edit($id)
    {
      
    }

    public function update(Request $request, $id)
    {
        $user = $request->user();
        if(!($this->laboratoryRepository->isOwner($user->id,$id))){
            return $this->failedResponse(['message'=>trans('auth.permission_deined')]);
        }

        $validator = $this->laboratoryValidator($request->all());
        if($validator->fails()){
            return $this->validateErrorResponse($validator->errors()->all());
        }

        $request_data = $request->only(['title','layout']);

        $data = array_filter($request_data, function($item){return $item!=null;});

        $user->laboratories()->where('id',$id)->update($data);
        $laboratory = $user->laboratories()->find($id);

        $products = $request->input('products');
        $laboratory->products()->syncWithoutDetaching($products);
        

        return $this->successResponse($laboratory?$laboratory:[]);
    }

    public function destroy(Request $request, $id)
    {
        $user = $request->user();
        if(!($this->laboratoryRepository->isOwner($user->id,$id))){
            return $this->failedResponse(['message'=>trans('auth.permission_deined')]);
        }
        $user->laboratories()->delete($id);
        return $this->successResponse(['id'=>$id]);

    }

    protected function laboratoryValidator(array $data)
    {
        return Validator::make($data, [
            'title' => 'required|max:255',
            'layout' => 'string',
            'products' => 'exists:products,id',
            'products.*' => 'exists:products,id'
        ]);        
    }
}
