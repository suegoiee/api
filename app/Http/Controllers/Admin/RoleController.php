<?php

namespace App\Http\Controllers\Admin;

use Auth;
use App\Admin;
use App\Role;
use App\Backend_functions;
use Illuminate\Http\Request;
use App\Policies\AdminPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;

class RoleController extends AdminController
{
    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->moduleName = 'role';
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $roles = Role::all();
        $functions = Backend_functions::all();
        $data = [
            'actionName'=>__FUNCTION__,
            'module_name'=> $this->moduleName,
            'actions'=>['new'],
            'table_data' => $roles,
            'table_head' =>['id','name'],
            'table_formatter' =>[''],
        ];
        return view("admin.list", $data);
    }

    public function create()
    {
        $functions = Backend_functions::all();
        $users = Admin::all();
        $data = [
            'actionName'=>__FUNCTION__,
            'module_name'=> $this->moduleName,
            'functions'=> $functions,
            'users'=>$users,
            'data'=>null,
        ];
        return view('admin.form',$data);
    }

    public function edit($id)
    {
        $role = Role::where('id', $id)->first();
        $functions = Backend_functions::all();
        $users = Admin::all();
        $associated_product = array();
        $data = [
            'actionName'=>__FUNCTION__,
            'module_name'=> $this->moduleName,
            'functions'=> $functions,
            'users'=>$users,
            'data' => $role,
        ];
        return view('admin.form',$data);
    }

    public function store(Request $request)
    {
        $validator = $this->roleCreateValidator($request->all(), null);
        $request_data = $request->only(['name']);
        $role = new Role($request_data);
        $role->save();
        $admin_ids = $request->admin_id;
        foreach($admin_ids as $admin_id){
            Admin::where('id', $admin_id)->update(['auth' => $role->id]);
        }
        $finctions = $request->function_id;
        $role->permissions()->sync($finctions);
        return $request->input('action')=="save_exit" ? redirect(url('/admin/'.str_plural($this->moduleName))) : redirect(url('/admin/'.str_plural($this->moduleName)));
    }

    public function update(Request $request, $id)
    {
        $role = Role::where("id", $id)->first();
        $role_users = $role->users;
        if($role_users){
            foreach($role_users as $role_user){
                $role_user->update(['auth' => 0]);
            }
        }
        $admin_ids = $request->admin_id;
        if($admin_ids){
            foreach($admin_ids as $admin_id){
                Admin::where('id', $admin_id)->update(['auth' => $id]);
            }
        }
        $finctions = $request->function_id;
        $role->permissions()->sync($finctions);
        return $request->input('action')=="save_exit" ? redirect(url('/admin/'.str_plural($this->moduleName))) : redirect(url('/admin/'.str_plural($this->moduleName).'/'.$id.'/edit'));
    }

    public function destroy(Request $request, $id = 0)
    {
        $ids = $id ? [$id]:$request->input('id',[]);
        foreach ($ids as $key => $value) {
            $role = Role::where('id', $value)->first();
            $role->permissions()->detach();
            $role->delete();
        }
        return $id ? redirect(url('/admin/'.str_plural($this->moduleName))):$this->successResponse(['id'=>$ids]);
    }

    protected function roleCreateValidator(array $data)
    {
        return Validator::make($data, [
            'name',
            'admin_id' => 'array',
            'function_id' => 'array',
        ]);        
    }

    protected function categoryUpdateValidator(array $data,$id)
    {
        return Validator::make($data, [
            'name' => 'string',
            'product_id' => 'array',
        ]);        
    }
}