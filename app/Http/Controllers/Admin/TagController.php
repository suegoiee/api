<?php

namespace App\Http\Controllers\Admin;

use App\Repositories\TagRepository;
use App\Repositories\StockRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
class TagController extends AdminController
{	
    protected $stockgRepository;

    public function __construct(Request $request, TagRepository $tagRepository, StockRepository $stockRepository)
    {
        parent::__construct($request);
        $this->moduleName='tag';
        $this->moduleRepository = $tagRepository;
        $this->stockRepository = $stockRepository;
        //$this->token = $this->clientCredentialsGrantToken();
    }

    public function index()
    {
        $tags = $this->moduleRepository->gets();

        $data = [
            'actionName'=>__FUNCTION__,
            'module_name'=> $this->moduleName,
            'actions'=>['new'],
            'table_data' => $tags,
            'table_head' =>['id','name'],
            'table_formatter' =>[],
        ];
        return view('admin.list',$data);
    }

    public function create()
    {
        $stocks = $this->stockRepository->gets();
        $data = [
            'actionName'=>__FUNCTION__,
            'module_name' => $this->moduleName,
            'data' => null,
            'stocks' => $stocks,
        ];
        return view('admin.form',$data);
    }

    public function edit($id)
    {
        $tag = $this->moduleRepository->get($id);
        $stocks = $this->stockRepository->gets();
        $data = [
            'actionName'=>__FUNCTION__,
            'module_name'=> $this->moduleName,
            'data' => $tag,
            'stocks' => $stocks,
        ];
        return view('admin.form',$data);
    }

    public function export(Request $request)
    {
        $where['id.in'] = $request->ids;
        $data = $this->moduleRepository->getsWith([], $where)->toArray();
        $sheet = [];
        $tag = ['編號' => null, '名稱' => null];
        foreach ($data as $row) {
            foreach ($row as $key => $value) {
                switch ($key) {
                    case "id":
                        $tag['編號'] = $value;
                        break;
                    case "name":
                        $tag['名稱'] = $value;
                        break;
                }
            }
            array_push($sheet, $tag);
            $tag = array_fill_keys(array_keys($tag), null);
        }
        $this->tableExport($sheet);
    }
}
