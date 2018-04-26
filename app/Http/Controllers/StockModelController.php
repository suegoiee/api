<?php

namespace App\Http\Controllers;

use App\Repositories\ProductRepository;
use App\Repositories\StockModelRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StockModelController extends Controller
{	
    protected $stockModelRepository;
    protected $productRepository;
    public function __construct(StockModelRepository $stockModelRepository, ProductRepository $productRepository)
    {
	   $this->stockModelRepository = $stockModelRepository;
       $this->productRepository = $productRepository;
    }

    public function index()
    {
        $stockModels = $this->stockModelRepository->gets();

        return $this->successResponse($stockModels);
    }

    public function getModelProducts(Request $request)
    {
        $stock_code = $request->input('stock','');
        $stockModels = $this->stockModelRepository->getsWith([],['data.like'=>'%"'.$stock_code.'"%']);
        $models = [];
        foreach ($stockModels as $key => $stockModel) {
           array_push($models, $stockModel->model);
        }
        $products = $this->productRepository->getsWith(['tags'=>function($query){$query->select('name');}, 'faqs'],['status'=>1,'model.in'=>$models])->makeHidden(['status', 'created_at', 'updated_at', 'deleted_at']);

        return $this->successResponse($products);
    }
}
