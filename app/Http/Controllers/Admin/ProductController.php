<?php
namespace App\Http\Controllers\Admin;

use App\Repositories\ProductRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
class ProductController extends AdminController
{	
    public function __construct(ProductRepository $productRepository)
    {
        $this->moduleName='product';
        $this->moduleRepository = $productRepository;
        $this->token = $this->clientCredentialsGrantToken();
    }

    public function index()
    {
        $product = $this->moduleRepository->getsWith(['tags','collections','avatar_small']);
        $data = [
            'module_name'=> $this->moduleName,
            'table_data' => $product,
            'table_head' =>['id','name','type','model','status'],
            'table_formatter' =>['status'],
        ];
        return view('admin.list',$data);
    }

    public function create()
    {
        $data = [
            'module_name'=> $this->moduleName,
            'data'=>null,
        ];
        return view('admin.form',$data);
    }

    public function edit($id)
    {

        $data = [
            'module_name'=> $this->moduleName,
            'data' => $this->moduleRepository->getWith($id,['tags','collections','avatar_small','avatar_detail']),
        ];
        return view('admin.form',$data);
    }
}
