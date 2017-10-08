<?php

namespace App\Http\Controllers\Admin;

use App\Repositories\TagRepository;
use App\Repositories\StockRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
class TagController extends AdminController
{	
    protected $stockgRepository;

    public function __construct(TagRepository $tagRepository, StockRepository $stockRepository)
    {
        $this->moduleName='tag';
        $this->moduleRepository = $tagRepository;
        $this->stockRepository = $stockRepository;
        $this->token = $this->clientCredentialsGrantToken();
    }

    public function index()
    {
        $tags = $this->moduleRepository->gets();

        $data = [
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
            'module_name'=> $this->moduleName,
            'data' => $tag,
            'stocks' => $stocks,
        ];
        return view('admin.form',$data);
    }
}
