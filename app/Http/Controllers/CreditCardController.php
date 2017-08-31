<?php

namespace App\Http\Controllers;

use App\Repositories\CreditCardRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CreditCardController extends Controller
{	

    protected $creditCardRepository;

    public function __construct(CreditCardRepository $creditCardRepository)
    {
	   $this->creditCardRepository = $creditCardRepository;
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
        $user = $request->user();
        $validator = $this->creditCardValidator($request->all());
        if($validator->fails()){
            return $this->validateErrorResponse($validator->errors()->all());
        }

        $request_data = $request->only(['number','month','year','check']);

        $creditCard = $user->creditCard()->create($request_data);

        return $this->successResponse($creditCard?$creditCard:[]);
    }

    public function show(Request $request, $id)
    {
        $user = $request->user();
        if(!($this->creditCardRepository->isOwner($user->id,$id))){
            return $this->failedResponse(['message'=>trans('auth.permission_deined')]);
        }

        $creditCard = $this->creditCardRepository->get($id);

        return $this->successResponse($creditCard?$creditCard:[]);
    }

    public function edit($id)
    {
      
    }

    public function update(Request $request, $id)
    {
        $user = $request->user();
        if(!($this->creditCardRepository->isOwner($user->id,$id))){
            return $this->failedResponse(['message'=>trans('auth.permission_deined')]);
        }

        $validator = $this->creditCardValidator($request->all());
        if($validator->fails()){
            return $this->validateErrorResponse($validator->errors()->all());
        }

        $request_data = $request->only(['number','month','year','check']);

        $data = array_filter($request_data, function($item){return $item!=null;});

        $creditCard = $this->creditCardRepository->update($id,$data);

        return $this->successResponse($creditCard?$creditCard:[]);
    }

    public function destroy(Request $request, $id)
    {
        $user = $request->user();
        if(!($this->creditCardRepository->isOwner($user->id,$id))){
            return $this->failedResponse(['message'=>trans('auth.permission_deined')]);
        }
        $this->creditCardRepository->delete($id);
        return $this->successResponse(['id'=>$id]);

    }

    protected function creditCardValidator(array $data)
    {
        return Validator::make($data, [
            'number' => 'required|max:255',
            'month' => 'required|max:2',
            'year'=>'required|max:4',
            'check'=>'required|max:6',
        ]);        
    }
}
