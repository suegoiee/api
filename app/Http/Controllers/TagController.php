<?php

namespace App\Http\Controllers;

use App\Repositories\TagRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TagController extends Controller
{	

    protected $tagRepository;

    public function __construct(TagRepository $tagRepository)
    {
	   $this->tagRepository = $tagRepository;
    }

    public function index()
    {
        $tags = $this->tagRepository->gets();

        return $this->successResponse($tags);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $validator = $this->tagValidator($request->all());
        if($validator->fails()){
            return $this->validateErrorResponse($validator->errors()->all());
        }
        
        $request_data = $request->only('name');
        $stock_ids = $request->input('stocks',[]);

        $tag = $this->tagRepository->create($request_data);

        if($tag){
            $tag->stocks()->attach($stock_ids);
        }

        return $this->successResponse($tag?$tag:[]);
    }

    public function show(Request $request, $id)
    {

        $tag = $this->tagRepository->get($id);

        return $this->successResponse($tag?$tag:[]);
    }

    public function edit($id)
    {
      
    }

    public function update(Request $request, $id)
    {
        $validator = $this->tagValidator($request->all(), $id);
        if($validator->fails()){
            return $this->validateErrorResponse($validator->errors()->all());
        }

        $request_data = $request->only('name');
        $stock_ids = $request->input('stocks',[]);
        $tag = $this->tagRepository->update($id,$request_data);
        if($tag){
            $tag->stocks()->sync($stock_ids);
        }
        return $this->successResponse($tag?$tag:[]);
    }

    public function destroy(Request $request, $id)
    {
        $this->tagRepository->delete($id);
        return $this->successResponse(['id'=>$id]);

    }

    protected function tagValidator(array $data,$id=0)
    {
        return Validator::make($data, [
            'name' => 'required|max:255|unique:tags,name,'.$id,
        ]);        
    }
}
