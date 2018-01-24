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
    
    public function list()
    {
        $stocks = $this->stockRepository->gets(['stock_code'])->map(function($item, $key){return $item->stock_code;});

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

        $request_data = $request->only(['stock_code','stock_name','stock_industries','industries','info','area','product','local_related_1','local_related_2','local_related_3','local_related_4','local_related_5','foreign_related','type']);

        $stock = $this->stockRepository->create($request_data);

        $events = $request->input('events',[]);
        foreach ($events as $key => $event) {
            if(isset($event['year'])||isset($event['content'])){
                $stock->events()->create(['year'=>$event['year'],'content'=>$event['content']]);
            }
        }
        $fieldName = ['products','areas','suppliers','customers','local_relateds','foreign_relateds'];
        foreach ($fieldName as $value) {
            $list_inputs = $request->input($value,[]);
            foreach ($list_inputs as $key => $list_input) {
                $list_input_name = isset($list_input['name'])? $list_input['name'] : '';
                $list_input_value = isset($list_input['value'])? $list_input['value'] : '';
                if($list_input_name!='' || $list_input_value!=''){
                    $stock->$value()->create(['name'=>$list_input_name,'value'=>$list_input_value]);
                }
            }
        }

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

        $request_data = $request->only(['stock_code','stock_name','stock_industries','industries','info','area','product','local_related_1','local_related_2','local_related_3','local_related_4','local_related_5','foreign_related','type']);

        $data = array_filter($request_data, function($item){return $item!=null;});

        $stock = $this->stockRepository->update($id,$data);

        $events = $request->input('events',[]);
        $event_ids = [];
        foreach ($events as $key => $event) {
            if(!isset($event['year']) && !isset($event['content'])){
                continue;
            }
            $event['year'] = isset($event['year']) ? $event['year']:'';
            $event['content'] = isset($event['content']) ? $event['content']:'';
            if($event['id']==0){
                $event_data = $stock->events()->create(['year'=>$event['year'],'content'=>$event['content']]);
            }else{
                $stock->events()->where('id',$event['id'])->update(['year'=>$event['year'],'content'=>$event['content']]);
                $event_data = $stock->events()->find($event['id']);
            }
            array_push($event_ids,$event_data->id);
        }
        $stock->events()->whereNotIn('id',$event_ids)->delete();

        $fieldName = ['products','areas','suppliers','customers','local_relateds','foreign_relateds'];
        foreach ($fieldName as $value) {
            $list_inputs = $request->input($value,[]);
            $list_input_ids = [];
            foreach ($list_inputs as $key => $list_input) {
                if(!isset($list_input['name']) && !isset($list_input['value'])){
                    continue;
                }

                $list_input_name    = isset($list_input['name']) ? $list_input['name']:'';
                $list_input_value   = isset($list_input['value']) ? $list_input['value']:'';
                
                if($list_input['id']==0){
                    $list_input_data = $stock->$value()->create(['name'=>$list_input_name,'value'=>$list_input_value]);
                }else{
                    $stock->$value()->where('id',$list_input['id'])->update(['name'=>$list_input_name,'value'=>$list_input_value]);
                    $list_input_data = $stock->$value()->find($list_input['id']);
                }
                array_push($list_input_ids,$list_input_data->id);
            }
            $stock->$value()->whereNotIn('id',$list_input_ids)->delete();
        }

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
            'stock_code'=>'required|max:4|unique:company_info,stock_code,'.$id.',no',
            'stock_name' => 'required|max:255',
            //'stock_industries' => 'required|max:255',
            'industries' => 'required|max:255',
            //'info' => 'required',
            //'area',
            //'product',
            //'local_related_1',
            //'local_related_2',
            //'local_related_3',
            //'local_related_4',
            //'local_related_5',
            //'foreign_related',
            'type' => 'required|max:255',
        ]);        
    }
}
