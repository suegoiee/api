<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Lang;
use App\Repositories\TagRepository;
use App\Repositories\UserRepository;
use App\Repositories\ExpertRepository;

class ExpertController extends AdminController
{
    public function __construct(Request $request, TagRepository $tagRepository, ExpertRepository $expertRepository, UserRepository $userRepository)
    {
        parent::__construct($request);
        $this->moduleName = 'expert';
        $this->moduleRepository = $expertRepository;
        $this->tagRepository = $tagRepository;
        $this->expertRepository = $expertRepository;
        $this->userRepository = $userRepository;
    }

    public function index()
    {
        $expert = $this->expertRepository->getsWith(['tags', 'user']);
        //dd($expert);
        $data = [
            'actionName'=>__FUNCTION__,
            'module_name'=> $this->moduleName,
            'actions'=>['new'],
            'table_data' => $expert,
            'table_head' =>['id','expert_name','user.name','user.email'],
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
            'users'=>$this->userRepository->gets(),
            'data'=>null,
        ];
        return view('admin.form',$data);
    }

    public function store(Request $request)
    {
        $validator = $this->referrerCreateValidator($request->all(), null);
        $request_data = $request->only(['expert_name', 'investment_style', 'introduction', 'investment_period', 'book', 'interview', 'user_id', 'experience', 'avatar']);
        $referrer = $this->expertRepository->create($request_data);
        $tags = $request->input('tags',[]);
        $referrer->tags()->attach($tags);
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
        $request_data = $request->only(['expert_name', 'investment_style', 'introduction', 'investment_period', 'book', 'interview', 'user_id', 'experience', 'avatar']);
        //dd($request_data, $id);
        $referrer = $this->expertRepository->update($id, $request_data);
        $tags = $request->input('tags',[]);
        $referrer->tags()->sync($tags);

        return $request->input('action')=="save_exit" ? redirect(url('/admin/'.str_plural($this->moduleName))) : redirect(url('/admin/'.str_plural($this->moduleName).'/'.$id.'/edit'));
    }

    public function edit($id)
    {
        $expert = $this->expertRepository->getWith($id,['tags', 'user']);
        $data = [
            'actionName'=>__FUNCTION__,
            'module_name'=> $this->moduleName,
            'tags'=>$this->tagRepository->gets(),
            'users'=>$this->userRepository->gets(),
            'data' => $expert,
        ];
        return view('admin.form',$data);
    }

    protected function referrerCreateValidator(array $data)
    {
        return Validator::make($data, [
            'expert_name' => 'required|string',
            'investment_style' => 'int',
            'introduction' => 'string',
            'investment_period' => 'string',
            'interview' => 'string',
            'book' => 'string',
            'tags' => 'array',
            'user_id',
            /*'interested' => 'int',*/
        ]);        
    }

    protected function referrerUpdateValidator(array $data,$id)
    {
        return Validator::make($data, [
            'expert_name' => 'required|string',
            'quota' => 'int',
            'introduction' => 'string',
            'host' => 'string',
            'suitable' => 'string',
            'tags' => 'array',
            'experts' => 'array',
            'image' => 'string',
            'user_id',
            /*'interested' => 'int',*/
        ]);        
    }
}
