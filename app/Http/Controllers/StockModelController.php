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
        $validator = $this->stockValidator($request->all());
        if($validator->fails()){
            return $this->validateErrorResponse($validator->errors()->all());
        }
        $stock_code = $request->input('stock_code','');

        $stockModels = $this->stockModelRepository->getsWith([],['data.like'=>'%"'.$stock_code.'"%']);
        $models = [];
        foreach ($stockModels as $key => $stockModel) {
           array_push($models, $stockModel->model);
        }
        $products = $this->productRepository->getsWith(['tags'=>function($query){$query->select('name');}, 'faqs','plans'=>function($query){$query->where('active',1);}],['status'=>1,'model.in'=>$models])->makeHidden(['status', 'created_at', 'updated_at','faq', 'deleted_at','prince','expiration']);
       return $this->successResponse($products);
    }
    protected function stockValidator(array $data)
    {
        return Validator::make($data, [
            'stock_code' => 'required|max:255',
        ]);        
    }
}
