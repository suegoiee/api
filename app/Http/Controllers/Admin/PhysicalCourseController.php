<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Validator;
use App\Traits\ImageStorage;
use App\Repositories\TagRepository;
use App\Repositories\UserRepository;
use App\Repositories\PlanRepository;
use App\Repositories\OrderRepository;
use App\Repositories\ExpertRepository;
use App\Repositories\ProfileRepository;
use App\Repositories\OrderCourseRepository;
use App\Repositories\PhysicalCourseRepository;

class PhysicalCourseController extends AdminController
{	
    use ImageStorage;

    protected $PhysicalCourseRepository;

    public function __construct(Request $request, PhysicalCourseRepository $PhysicalCourseRepository, TagRepository $tagRepository, ExpertRepository $expertRespository, PlanRepository $planRepository, OrderCourseRepository $OrderCourseRepository, UserRepository $UserRepository, OrderRepository $OrderRepository, ProfileRepository $ProfileRepository)
    {
        parent::__construct($request);
        $this->moduleName = 'physical_course';
        $this->PhysicalCourseRepository = $PhysicalCourseRepository;
        $this->moduleRepository = $PhysicalCourseRepository;
        $this->tagRepository = $tagRepository;
        $this->expertRespository = $expertRespository;
        $this->planRepository = $planRepository;
        $this->OrderCourseRepository = $OrderCourseRepository;
        $this->UserRepository = $UserRepository;
        $this->OrderRepository = $OrderRepository;
        $this->ProfileRepository = $ProfileRepository;
    }

    public function index()
    {
        $physical = $this->PhysicalCourseRepository->getsWith(['tags', 'experts']);
        $data = [
            'actionName'=>__FUNCTION__,
            'module_name'=> $this->moduleName,
            'actions'=>['new'],
            'table_data' => $physical,
            'table_head' =>['id','name','date', 'location','quota'],
            'table_formatter' =>[''],
        ];
        return view('admin.list', $data);
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
            return redirect()->back()->withInput($request->all())->withErrors($validator);
        }
        $request_data = $request->only(['name', 'date', 'end_date', 'quota', 'introduction', 'host', 'suitable', 'location', 'image', 'seo', 'electric_ticket', 'allow_freecourse']);
        if($request->file('image')){
            $path = $this->storeImage($request->file('image'), 'physical_course');
            $request_data['image'] = $path;
        }
        else{
            $request_data['image'] = '';
        }
        $referrer = $this->PhysicalCourseRepository->create($request_data);
        $plans = $request->input('plans',[]);
        foreach($plans as $plan){
            $tmp = $this->planRepository->create($plan);
            $referrer->plan()->attach($tmp);
        }
        $tags = $request->input('tags',[]);
        $referrer->tags()->attach($tags);
        $experts = $request->input('experts',[]);
        $referrer->experts()->attach($experts);
        return $request->input('action')=="save_exit" ? redirect(url('/admin/'.str_plural($this->moduleName))) : redirect(url('/admin/'.str_plural($this->moduleName).'/'.$referrer->id.'/edit'));
    }

    public function update(Request $request, $id)
    {
        if(!$id){
            return redirect()->back();
        }
        $validator = $this->referrerUpdateValidator($request->all(), $id);
        if($validator->fails()){
            return redirect()->back()->withInput($request->all())->withErrors($validator);
        }
        $request_data = $request->only(['name','date', 'end_date', 'quota', 'introduction', 'host', 'suitable', 'location', 'image', 'seo', 'electric_ticket', 'status', 'allow_freecourse']);
        if($request->file('image')){
            $path = $this->storeImage($request->file('image'), 'online_course');
            $request_data['image'] = $path;
        }
        elseif($request->has('delete_image') && !$request->file('image')){
            $request_data['image'] = '';
        }
        else{
            $request_data = $request->only(['name','date', 'end_date', 'quota', 'introduction', 'host', 'suitable', 'seo', 'electric_ticket', 'status', 'allow_freecourse']);
        }
        $referrer = $this->PhysicalCourseRepository->update($id, $request_data);
        $plans = $request->input('plans',[]);
        foreach($plans as $plan){
            if($plan["id"] == 'new'){
                $tmp = $this->planRepository->create($plan);
                $referrer->plan()->attach($tmp);
            }
            else{
                $this->planRepository->update($plan["id"], $plan);
            }
        }
        $tags = $request->input('tags',[]);
        $referrer->tags()->sync($tags);
        $experts = $request->input('experts',[]);
        $referrer->experts()->sync($experts);

        return $request->input('action')=="save_exit" ? redirect(url('/admin/'.str_plural($this->moduleName))) : redirect(url('/admin/'.str_plural($this->moduleName).'/'.$id.'/edit'));
    }

    public function edit($id)
    {
        $product =  $this->PhysicalCourseRepository->getWith($id,['tags', 'experts', 'plan', 'orderCourse']);
        $students = array();
        foreach($product->orderCourse as $orderCourse){
            $user = $this->UserRepository->getWith($this->OrderRepository->getWith($orderCourse->order_id)->user_id);
            $user->quantity = $orderCourse->quantity;
            $user->source = $orderCourse->source;
            $user->remarks = $orderCourse->remarks;
            $user->paid = $orderCourse->paid;
            //dd($user, $orderCourse);
            array_push($students, $user);
        }
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
            'students' => $students
        ];
        return view('admin.form', $data);
    }
    
    protected function referrerCreateValidator(array $data)
    {
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
            'location',
            'allow_freecourse',
        ]);        
    }

    protected function referrerUpdateValidator(array $data,$id)
    {
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
            'location',
            'allow_freecourse',
        ]);        
    }
}
