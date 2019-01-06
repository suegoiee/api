<?php
namespace App\Http\Controllers\Admin;

use App\Traits\ImageStorage;
use App\Repositories\EdmRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EdmController extends AdminController
{	
    use ImageStorage;
    public function __construct(Request $request, EdmRepository $edmRepository)
    {
        parent::__construct($request);
        $this->moduleName='edm';
        $this->moduleRepository = $edmRepository;

        //$this->token = $this->clientCredentialsGrantToken();
    }

    public function index(Request $request)
    {
        $edm = $this->moduleRepository->getsWith([],[],['status'=>'DESC','sort'=>'ASC','updated_at'=>'DESC']);
        $data = [
            'actionName'=>__FUNCTION__,
            'module_name'=> $this->moduleName,
            'actions'=>['new'],
            'table_data' => $edm,
            'table_head' =>['name', 'status', 'updated_at'],
            'table_formatter' =>['status'],
        ];
        return view('admin.list',$data);
    }

    public function create()
    {
        $data = [
            'actionName'=>__FUNCTION__,
            'module_name'=> $this->moduleName,
            'data'=>null,
        ];
        return view('admin.form',$data);
    }
    public function store(Request $request)
    {
        $data = $request->only(['name', 'status', 'sort']);
        $data['sort'] = 0; 
        $edm = $this->moduleRepository->create($data);
        $input_images = $request->only('images');
        $input_images = is_array($input_images['images']) ? $input_images['images']:[];
        foreach ($input_images as $key => $input_image) {
            $edm->images()->create([ 
                'title' =>isset($input_image['title'])? $input_image['title']:'',
                'path' => isset($input_image['image'])? $this->createImage($input_image['image'], $edm->id, 'edms') : '',
                'link' => isset($input_image['link'])? $input_image['link']:'',
                'sort' =>$input_image['sort'],
                'seo' =>isset($input_image['seo'])? $input_image['seo']:'',
            ]);
        }
        return $this->adminResponse($request,['status'=>'success', 'data'=>$edm? $edm : []]);
    }
    public function edit($id)
    {

        $data = [
            'actionName'=>__FUNCTION__,
            'module_name'=> $this->moduleName,
            'data' => $this->moduleRepository->getWith($id),
        ];
        return view('admin.form',$data);
    }
    public function update(Request $request, $id = 0)
    {
        $request_data = $request->only(['name', 'status', 'sort']);
        $data = array_filter($request_data, function($item){return $item!=null;});
        $edm = $this->moduleRepository->update($id, $data);
        $images = $request->only('images');
        $images = is_array($images['images']) ? $images['images']: [];
        $image_ids = [];
        foreach ($images as $key => $image) {
            $image_id = $image['id'];
            if($image['title']=="" && $image['link']=="" && ($image_id=='0' && !isset($image['image']))){
                continue;
            }
            $image_data=[
                'title'=>$image['title'],
                'link'=>$image['link'],
                'sort'=>$image['sort'],
                'seo' =>isset($image['seo'])? $image['seo']:'',
            ];
            if(isset($image['image'])){
                $image_data['path'] = $this->createImage($image['image'], $edm->id, 'edms');
            }else{
                $image_data['path'] = '';
            }
            
            if($image_id=='0'){
                $image_model = $edm->images()->create($image_data);
                $image_id = $image_model->id;
            }else{
                $edm->images()->where('id',$image_id)->update($image_data);
            }
            array_push($image_ids,  $image_id);
        }
        $deleted_images = $edm->images()->whereNotIn('id', $image_ids)->get();
        $edm->images()->whereNotIn('id', $image_ids)->delete();
        $deleted_paths = $deleted_images->map(function($item,$key){return $item->path;})->all();
        $this->destroyAvatar( $deleted_paths);
        return $this->adminResponse($request,['status'=>'success', 'data'=>$edm? $edm : []]);
    }
    public function sorted(Request $request)
    {   
        $edm_ids = $request->input('edms', []);
        foreach ($edm_ids as $key => $edm_id) {
           $this->moduleRepository->update($edm_id, ['sort'=>$key]);
        }
        return redirect('admin/edms');
    }
}
