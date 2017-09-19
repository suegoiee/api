<?php
namespace App\Http\Controllers\Admin;
use App\Repositories\TagRepository;
use App\Repositories\ProductRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
class ProductController extends AdminController
{	
    protected $tagRepository;

    public function __construct(ProductRepository $productRepository, TagRepository $tagRepository)
    {
        $this->moduleName='product';
        $this->moduleRepository = $productRepository;
        $this->tagRepository = $tagRepository;

        $this->token = $this->clientCredentialsGrantToken();
    }

    public function index()
    {
        $product = $this->moduleRepository->getsWith(['tags','collections','avatar_small']);
        $data = [
            'module_name'=> $this->moduleName,
            'actions'=>['new'],
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
            'tags'=>$this->tagRepository->gets(),
            'collections'=>$this->moduleRepository->gets(),
            'data'=>null,
        ];
        return view('admin.form',$data);
    }

    public function edit($id)
    {

        $data = [
            'module_name'=> $this->moduleName,
            'tags'=>$this->tagRepository->gets(),
            'collections'=>$this->moduleRepository->gets(),
            'data' => $this->moduleRepository->getWith($id,['tags','collections','avatar_small','avatar_detail']),
        ];
        return view('admin.form',$data);
    }
}
