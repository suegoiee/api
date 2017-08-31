<?php

namespace App\Http\Controllers;

use App\Repositories\StockRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StockController extends Controller
{	
    protected $StockRepository;
    public function __construct(StockRepository $stockRepository)
    {
	   $this->stockRepository = $stockRepository;
    }

    public function index()
    {
        $stocks = $this->stockRepository->gets();

        return $this->successResponse($stocks?$stocks:[]);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $validator = $this->stockValidator($request->all());
        if($validator->fails()){
            return $this->validateErrorResponse($validator->errors()->all());
        }

        $request_data = $request->only(['name','abbrev','code']);

        $stock = $this->stockRepository->create($request_data);

        return $this->successResponse($stock?$stock:[]);
    }

    public function show(Request $request, $id)
    {
        
        $stock = $this->stockRepository->get($id);

        return $this->successResponse($stock?$stock:[]);
    }

    public function edit($id)
    {
      
    }

    public function update(Request $request, $id)
    {
        $validator = $this->stockValidator($request->all(), $id);
        if($validator->fails()){
            return $this->validateErrorResponse($validator->errors()->all());
        }

        $request_data = $request->only(['name','abbrev','code']);
        $data = array_filter($request_data, function($item){return $item!=null;});

        $stock = $this->stockRepository->update($id,$data);

        return $this->successResponse($stock?$stock:[]);
    }

    public function destroy(Request $request, $id)
    {
        $this->stockRepository->delete($id);
        return $this->successResponse(['id'=>$id]);

    }

    protected function stockValidator(array $data,$id=0)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'abbrev' => 'required|max:255',
            'code'=>'required|max:6|unique:stocks,code,'.$id,
        ]);        
    }
}
