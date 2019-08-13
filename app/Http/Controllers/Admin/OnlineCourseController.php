<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Lang;
use App\Repositories\TagRepository;
use App\Repositories\ExpertRepository;
use App\Repositories\OnlineCourseRepository;

class OnlineCourseController extends AdminController
{	
    protected $OnlineCourseRepository;

    public function __construct(Request $request, OnlineCourseRepository $OnlineCourseRepository, TagRepository $tagRepository, ExpertRepository $expertRespository)
    {
        parent::__construct($request);
        $this->moduleName = 'online_course';
        $this->OnlineCourseRepository = $OnlineCourseRepository;
        $this->moduleRepository = $OnlineCourseRepository;
        $this->tagRepository = $tagRepository;
        $this->expertRespository = $expertRespository;
    }

    public function index()
    {
        $online = $this->OnlineCourseRepository->getsWith(['tags', 'experts']);
        //dd($online);
        $data = [
            'actionName'=>__FUNCTION__,
            'module_name'=> $this->moduleName,
            'actions'=>['new'],
            'table_data' => $online,
            'table_head' =>['id','name','date','quota'],
            'table_formatter' =>[''],
        ];
        return view('admin.list',$data);
    }

    public function create()
    {
        $data = [
            'actionName'=>__FUNCTION__,
            'module_name'=> $this->moduleName,
            'tags'=>$this->tagRepository->gets(),
            'experts'=>$this->expertRespository->gets(),
            'data'=>null,
        ];
        return view('admin.form',$data);
    }

    public function store(Request $request)
    {
        $validator = $this->referrerCreateValidator($request->all(), null);
        $request_data = $request->only(['name','date', 'quota', 'introduction', 'host', 'suitable', 'image']);
        $referrer = $this->OnlineCourseRepository->create($request_data);
        $tags = $request->input('tags',[]);
        $referrer->tags()->attach($tags);
        $experts = $request->input('experts',[]);
        $referrer->experts()->attach($experts);
        //$course = $this->dispatchNow(CreateOnlineCourse::fromRequest($validator));
        return $request->input('action')=="save_exit" ? redirect(url('/admin/'.str_plural($this->moduleName))) : redirect(url('/admin/'.str_plural($this->moduleName).'/'.$referrer->id.'/edit'));
    }

    public function update(Request $request, $id)
    {
        //dd($request, $id);
        if(!$id){
            return redirect()->back();
        }
        $validator = $this->referrerUpdateValidator($request->all(), $id);
        if($validator->fails()){
            return redirect()->back()->withInput($request->all())->withErrors($validator);
        }
        //dd($validator);
        $request_data = $request->only(['name','date', 'quota', 'introduction', 'host', 'suitable', 'image']);
        $referrer = $this->OnlineCourseRepository->update($id, $request_data);
        $tags = $request->input('tags',[]);
        $referrer->tags()->sync($tags);
        $experts = $request->input('experts',[]);
        $referrer->experts()->sync($experts);

        return $request->input('action')=="save_exit" ? redirect(url('/admin/'.str_plural($this->moduleName))) : redirect(url('/admin/'.str_plural($this->moduleName).'/'.$id.'/edit'));
    }

    public function edit($id)
    {
        $product =  $this->OnlineCourseRepository->getWith($id,['tags', 'experts']);
        //$product['date'] = date("Y-m-dTH:i", strtotime($product['date']));
        $dt = Carbon::createFromFormat('Y-m-d H:i:s', $product['date']);
        $product['date'] = $dt->format('Y-m-d\TH:i:s');
        $data = [
            'actionName'=>__FUNCTION__,
            'module_name'=> $this->moduleName,
            'tags'=>$this->tagRepository->gets(),
            'experts'=>$this->expertRespository->gets(),
            'data' => $product,
        ];
        return view('admin.form',$data);
    }
    
    protected function referrerCreateValidator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string',
            'date',
            'quota' => 'int',
            'introduction' => 'string',
            'host' => 'string',
            'suitable' => 'string',
            'tags' => 'array',
            'experts' => 'array',
            'image' => 'string',
            /*'interested' => 'int',*/
        ]);        
    }

    protected function referrerUpdateValidator(array $data,$id)
    {
        return Validator::make($data, [
            'name' => 'required|string',
            'quota' => 'int',
            'introduction' => 'string',
            'host' => 'string',
            'suitable' => 'string',
            'tags' => 'array',
            'experts' => 'array',
            'image' => 'string',
            /*'interested' => 'int',*/
        ]);        
    }
}