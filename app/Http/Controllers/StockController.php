<?php

namespace App\Http\Controllers;

use App\Repositories\StockRepository;
use App\Repositories\StockIndustryRepository;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StockController extends Controller
{	
    protected $stockRepository;
    protected $stockIndustryRepository;
    public function __construct(StockRepository $stockRepository, StockIndustryRepository $stockIndustryRepository) 
    {
	   $this->stockRepository = $stockRepository;
       $this->stockIndustryRepository = $stockIndustryRepository;
    }

    public function index()
    {
        $stocks = $this->stockRepository->gets();

        return $this->successResponse($stocks?$stocks:[]);
    }

    public function lists()
    {
        $stocks = $this->stockRepository->getsWith([],['stock_industries.<>'=>7])->map(function($item, $key){
            return $item->stock_code;
            });
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

        $request_data['industries'] = isset($request_data['industries']) ? $request_data['industries'] :'無';
        $stock = $this->stockRepository->create($request_data);
        $stockIndustry = $this->stockIndustryRepository->getBy(['stock_code'=>$request_data['stock_code']]);
        if($stockIndustry){
            if($request_data['stock_industries']==0){
                $stockIndustry->destroy();
            }else{
                $stockIndustry->update(['industry_id'=>$request_data['stock_industries']]);
            }
        }else{
            $this->stockIndustryRepository->create(['stock_code'=>$request_data['stock_code'], 'industry_id'=>$request_data['stock_industries']]);
        }



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
                $input_data = ['name'=>$list_input_name,'value'=>$list_input_value];
                $can_create = $list_input_name!='' || $list_input_value!='';
                if($value == 'products'){
                    $list_input_year = isset($list_input['year'])? $list_input['year'] : '';
                    $input_data['year'] = $list_input_year;
                    $can_create = $can_create || $list_input_year != '';
                }
                if($can_create){
                    $stock->$value()->create($input_data);
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
        $request_data['industries'] = isset($request_data['industries']) ? $request_data['industries'] :'無';
        $data = $request_data;//array_filter($request_data, function($item){return $item!=null;});

        $stock = $this->stockRepository->update($id,$data);
        $stockIndustry = $this->stockIndustryRepository->getBy(['stock_code'=>$request_data['stock_code']]);
        if($stockIndustry){
            if($request_data['stock_industries']==0){
                $stockIndustry->delete();
            }else{
                $stockIndustry->update(['industry_id'=>$request_data['stock_industries']]);
            }
        }else{
            $this->stockIndustryRepository->create(['stock_code'=>$request_data['stock_code'], 'industry_id'=>$request_data['stock_industries']]);
        }

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

                $list_input_name    = isset($list_input['name']) ? $list_input['name']:'';
                $list_input_value   = isset($list_input['value']) ? $list_input['value']:'';
                
                $input_data = ['name'=>$list_input_name,'value'=>$list_input_value];
                $can_update = $list_input_name!='' || $list_input_value!='';

                if($value == 'products'){
                    $list_input_year = isset($list_input['year'])? $list_input['year'] : '';
                    $input_data['year'] = $list_input_year;
                    $can_update = $can_update || $list_input_year != '';
                }
                if($can_update){
                    if($list_input['id']==0){
                        $list_input_data = $stock->$value()->create($input_data);
                    }else{
                        $stock->$value()->where('id',$list_input['id'])->update($input_data);
                        $list_input_data = $stock->$value()->find($list_input['id']);
                    }
                    array_push($list_input_ids,$list_input_data->id);
                }
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
            'stock_code'=>'required|max:10|unique:company_info,stock_code,'.$id.',no',
            'stock_name' => 'required|max:255',
            //'stock_industries' => 'required|max:255',
            'industries' => 'max:255',
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
