<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Lang;
use App\Traits\ImageStorage;
use App\Repositories\TagRepository;
use App\Repositories\ExpertRepository;
use App\Repositories\OnlineCourseRepository;

class OnlineCourseController extends AdminController
{	
    use ImageStorage;

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
        if($validator->fails()){
            dd($validator);
            return redirect()->back()->withInput($request->all())->withErrors($validator);
        }
        $request_data = $request->only(['name','date', 'end_date', 'quota', 'introduction', 'host', 'suitable', 'image', 'seo', 'electric_ticket', 'status']);
        if($request->file('image')){
            $path = $this->storeImage($request->file('image'), 'online_course');
            $request_data['image'] = $path;
        }
        else{
            $request_data['image'] = '';
        }
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
        if(!$id){
            return redirect()->back();
        }
        $validator = $this->referrerUpdateValidator($request->all());
        if($validator->fails()){
            dd($validator);
            return redirect()->back()->withInput($request->all())->withErrors($validator);
        }
        $request_data = $request->only(['name','date', 'end_date', 'quota', 'introduction', 'host', 'suitable', 'image', 'seo', 'electric_ticket', 'status']);
        if($request->file('image')){
            $path = $this->storeImage($request->file('image'), 'online_course');
            $request_data['image'] = $path;
        }
        else{
            $request_data['image'] = '';
        }
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
        if($product['date']){
            $dt = Carbon::createFromFormat('Y-m-d H:i:s', $product['date']);
            $product['date'] = $dt->format('Y-m-d\TH:i:s');
        }
        if($product['end_date']){
            $dt = Carbon::createFromFormat('Y-m-d H:i:s', $product['end_date']);
            $product['end_date'] = $dt->format('Y-m-d\TH:i:s');
        }
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
            'end_date',
            'quota' => 'int',
            'introduction' => 'string',
            'seo',
            'electric_ticket',
            'status' => 'int',
            'host' => 'string',
            'suitable' => 'string',
            'tags' => 'array',
            'experts' => 'array',
            'image',
        ]);        
    }

    protected function referrerUpdateValidator(array $data)
    {
        //dd($data);
        return Validator::make($data, [
            'name' => 'required|string',
            'date',
            'end_date',
            'quota' => 'int',
            'introduction',
            'seo',
            'electric_ticket',
            'status' => 'int',
            'host',
            'suitable',
            'tags' => 'array',
            'experts' => 'array',
            'image',
        ]);        
    }
}
