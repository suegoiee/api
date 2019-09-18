<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Lang;
use App\Repositories\UserRepository;
use App\Repositories\ProductRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\CategoryProductRepository;

class ForumCategoeyController extends AdminController
{
    public function __construct(Request $request, UserRepository $userRepository, ProductRepository $productRepository, CategoryRepository $categoryRepository, CategoryProductRepository $categoryProductRepository)
    {
        parent::__construct($request);
        $this->moduleName = 'forumCategory';
        $this->productRepository = $productRepository;
        $this->userRepository = $userRepository;
        $this->categoryRepository = $categoryRepository;
        $this->moduleRepository = $categoryRepository;
        $this->categoryProductRepository = $categoryProductRepository;
    }

    public function index()
    {
        $category = $this->categoryRepository->getsWith(['categoryProductRelation']);
        $data = [
            'actionName'=>__FUNCTION__,
            'module_name'=> $this->moduleName,
            'actions'=>['new'],
            'table_data' => $category,
            'table_head' =>['id','name','slug'],
            'table_formatter' =>[''],
        ];
        return view('admin.list',$data);
    }

    public function create()
    {
        $category = $this->categoryRepository->getsWith(['categoryProductRelation']);
        $data = [
            'actionName'=>__FUNCTION__,
            'module_name'=> $this->moduleName,
            'categories'=> $category,
            'products'=> $this->productRepository->gets(),
            'data'=>null,
        ];
        return view('admin.form',$data);
    }

    public function store(Request $request)
    {
        $validator = $this->categoryCreateValidator($request->all(), null);
        $request_data = $request->only(['name', 'product_id']);
        foreach($request_data['name'] as $key => $new_category_name){
            $tmp = array();
            $tmp['name'] = $new_category_name;
            $tmp['slug'] = $new_category_name;
            $category = $this->categoryRepository->create($tmp);
            $tmp = array();
            $tmp['category_id'] = $category->id;
            foreach($request_data['product_id'][$key] as $new_product_id){
                $products = $request->input('product_id',[]);
                $tmp['product_id'] = $new_product_id;
                $this->categoryProductRepository->create($tmp);
            }
        }
        return $request->input('action')=="save_exit" ? redirect(url('/admin/'.str_plural($this->moduleName))) : redirect(url('/admin/'.str_plural($this->moduleName)));
    }

    public function update(Request $request, $id)
    {
        if(!$id){
            return redirect()->back();
        }
        $validator = $this->categoryUpdateValidator($request->all(), $id);
        if($validator->fails()){
            return redirect()->back()->withInput($request->all())->withErrors($validator);
        }
        $request_data = $request->only(['name']);
        $request_data['slug'] = $request['name'];
        $category = $this->categoryRepository->update($id, $request_data);
        $this->categoryProductRepository->deleteByCategoryid($id);
        if($request['product_id'] && count($request['product_id']) > 0){
            foreach($request['product_id'] as $product_id){
                $tmp['category_id'] = $id;
                $tmp['product_id'] = $product_id;
                $this->categoryProductRepository->create($tmp);
            }
        }
        return $request->input('action')=="save_exit" ? redirect(url('/admin/'.str_plural($this->moduleName))) : redirect(url('/admin/'.str_plural($this->moduleName).'/'.$id.'/edit'));
    }

    public function edit($id)
    {
        $category = $this->categoryRepository->getWith($id, ['categoryProductRelation']);
        $associated_product = array();
        $data = [
            'actionName'=>__FUNCTION__,
            'module_name'=> $this->moduleName,
            'products'=> $this->productRepository->gets(),
            'data' => $category,
        ];
        return view('admin.form',$data);
    }

    public function destroy(Request $request, $id = 0)
    {
        $ids = $id ? [$id]:$request->input('id',[]);
        foreach ($ids as $key => $value) {
            $this->moduleRepository->get($value)->categoryProductRelation()->delete();
            $this->moduleRepository->delete($value);
        }
        return $id ? redirect(url('/admin/'.str_plural($this->moduleName))):$this->successResponse(['id'=>$ids]);
    }

    protected function categoryCreateValidator(array $data)
    {
        return Validator::make($data, [
            'name',
            'product_id' => 'array',
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