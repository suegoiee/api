<?php

namespace App\Http\Controllers;

use Route;
use App\User;
use App\Traits\ImageStorage;
use App\Repositories\EdmRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EdmController extends Controller
{	
    use ImageStorage;

    protected $edmRepository;

    public function __construct(
        EdmRepository $edmRepository)
    {
	   $this->edmRepository = $edmRepository;
    }

    public function index()
    {
        $edms=$this->edmRepository->gets();
        return $this->successResponse($edms);
    }
    public function onPublishList()
    {
        $edms = $this->edmRepository->getsWith(['images'], ["status"=>1]);
        return $this->successResponse($edms->makeHidden(['status']));
    }

    public function create()
    {
        //
    }

    public function store(Request $request, $module_id = 0)
    {
        $validator = $this->edmValidator($request->all());
        if($validator->fails()){
            return $this->validateErrorResponse($validator->errors()->all());
        }

        $data = $request->only(['name', 'status', 'sort']);
        $data['sort'] = 0; 
        $edm = $this->edmRepository->create($data);
        $input_images = $request->only('images');
        $input_images = $input_images['images'];
        foreach ($input_images as $key => $input_image) {
            if($input_image['image']){
                $path = $this->createImage($input_image['image'], $edm->id, 'edms');
            }else{
                $path = '';
            }
            $edm->images()->create([ 
                'title' =>$input_image['title'],
                'path' => $path,
                'link' =>$input_image['link'],
                'sort' =>$input_image['sort'],
                'seo' =>$input_image['seo'],
            ]);
        }
        return $this->successResponse($edm);
    }

    public function show(Request $request, $module_id = 0)
    {
        $edm = $this->edmRepository->get($module_id);

        return $this->successResponse($edm? $edm :[]);
    }

    public function onPublish(Request $request, $module_id = 0)
    {
        $edm = $this->edmRepository->getBy(["id" => $module_id, "status"=>1],['images']);
        
        return $this->successResponse($edm? $edm :[]);
    }

    public function edit($id)
    {
      
    }

    public function update(Request $request, $module_id = 0)
    {
        $validator = $this->edmValidator($request->all());
        if($validator->fails()){
            return $this->validateErrorResponse($validator->errors()->all());
        }
        $request_data = $request->only(['name', 'status', 'sort']);
        $data = array_filter($request_data, function($item){return $item!=null;});
        $edm = $this->edmRepository->update($module_id, $data);
        $images = $request->only('images');
        $images = $images['images'];
        $image_ids = [];
        foreach ($images as $key => $image) {
            $image_id = $image['id'];
            $image_data=[
                'title'=>$image['title'],
                'link'=>$image['link'],
                'sort'=>$image['sort'],
                'seo'=>$image['seo'],
            ];
            if($image['image']){
                $image_data['path'] = $this->createImage($image['image'], $edm->id, 'edms');
            }else{
                $image_data['path'] = '';
            }
            if($image_id!='0'){
                $image_model = $edm->images()->create($image_data);
                $image_id = $image_model->id;
            }else{
                $edm->images()->where('id',$image_id)->update($image_data);
            }
            array_push($image_ids,  $image_id);
        }
        $edm->images()->whereNotIn('id', $image_ids)->delete();
        return $this->successResponse($edm ? $edm : []);
    }

    public function destroy(Request $request, $module_id = 0)
    {
        $id = $request->input('deleted');
        if($id){
            $ids = is_array($id)? $id : [$id];
            $edms = $this->edmRepository->getsWith([],['id.in'=>$ids]);
            $edms->delete();
            $image_paths = collect([]);
            foreach ($edms as $key => $edm) {
               $image_paths = $image_paths->concat($edm->images->map(function($item,$key){return $item->path;}));
            }
            $deleted = $image_paths->all();
        }else{
            $edm =  $this->edmRepository->get($module_id);
            $images = $edm->images;
            $edm->images()->delete();
            $deleted = $images->map(function($item,$key){return $item->path;})->all();
        }
        $this->destroyAvatar($deleted);
        return $this->successResponse(['path'=>$deleted]);
    }

    protected function edmValidator(array $data)
    {
        return Validator::make($data, [
        ]);
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
