<?php

namespace App\Http\Controllers;

use App\Repositories\MessageRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Mail\Message;

class MessageController extends Controller
{	
    protected $messageRepository;

    public function __construct(MessageRepository $messageRepository)
    {
	   $this->messageRepository = $messageRepository;
    }

    public function index()
    {

    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $validator = $this->messageValidator($request->all());
        if($validator->fails()){
            return $this->validateErrorResponse($validator->errors()->all());
        }

        $request_data = $request->only(['name','email','category','content']);
        $request_data['category'] =  $request_data['category'] ? $request_data['category'] :'others';
        $request_data['content'] =  $request_data['content'] ? $request_data['content'] :'';
        $message = $this->messageRepository->create($request_data);
        Mail::to('shouwda@uanalyze.com.tw')->queue(new Message($message));

        return $this->successResponse($message?$message->makeHidden(['updated_at']):[]);
    }

    public function show(Request $request, $id)
    {
        $message = $this->messageRepository->get($id);

        return $this->successResponse($message?$message:[]);
    }

    public function edit($id)
    {
      
    }

    public function update(Request $request, $id)
    {
        $validator = $this->messageValidator($request->all());
        if($validator->fails()){
            return $this->validateErrorResponse($validator->errors()->all());
        }

        $request_data = $request->only(['name','email','category','content','status']);
        $data = array_filter($request_data, function($item){return $item!=null;});

        $message = $this->messageRepository->update($id,$data);

        return $this->successResponse($message?$message:[]);
    }

    public function destroy(Request $request, $id)
    {
        $this->messageRepository->delete($id);
        return $this->successResponse(['id'=>$id]);
    }

    protected function messageValidator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255',
        ]);        
    }
}
