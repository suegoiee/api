<?php

namespace App\Http\Controllers\Admin;

use App\Repositories\TagRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
class TagController extends AdminController
{	
    public function __construct(TagRepository $tagRepository)
    {
        $this->moduleName='tag';
        $this->moduleRepository = $tagRepository;
        $this->token = $this->clientCredentialsGrantToken();
    }

    public function index()
    {
        $tags = $this->moduleRepository->gets();

        $data = [
            'module_name'=> $this->moduleName,
            'table_data' => $tags,
            'table_head' =>['id','name'],
            'table_formatter' =>[],
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
            'data' => $this->moduleRepository->get($id),
        ];
        return view('admin.form',$data);
    }
}
