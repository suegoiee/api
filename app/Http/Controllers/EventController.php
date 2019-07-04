<?php

namespace App\Http\Controllers;

use App\Repositories\EventRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
class EventController extends Controller
{	
    protected $eventRepository;

    public function __construct(EventRepository $eventRepository)
    {
	   $this->eventRepository = $eventRepository;
    }

    public function index(Request $request)
    {
        $events = $this->eventRepository->getsWith([],['status'=>1]);
        return $this->successResponse($events);
    }

    public function productEvents(Request $request)
    {
        $products = $request->input('products',[]);
        $event_products = $this->checkEvents($products);
        
        return $this->successResponse($event_products);
    }
    function checkEvents($products)
    {
        $products = collect($products);
        $events = $this->eventRepository->getsWith(['condition_products','products'],['status'=>1]);
        $bonus_products = [];
        foreach ($events as $key => $event) {
            if($event->type == 1){
                $pass = true;
                foreach ($event->condition_products as $key => $condition_product) {
                    $product = $products->where('id', $condition_product->id)->first();
                    if($product){
                        if($product['quantity'] < $condition_product->pivot->quantity){
                            $pass = false ;
                            break;
                        }
                    }else{
                        $pass = false ;
                        break;
                    }
                }

                if($pass){
                    array_push($bonus_products, ['name'=>$event->name]);
                }
            }
        }
        return $bonus_products;
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $validator = $this->eventValidator($request->all());
        if($validator->fails()){
            return $this->validateErrorResponse($validator->errors()->all());
        }

        $request_data = $request->only(['name', 'started_at', 'ended_at', 'type', 'status']);
        $request_data['started_at'] = isset($request_data['started_at']) && $request_data['started_at']!="" ? $request_data['started_at'] : null;
        $request_data['ended_at'] = isset($request_data['ended_at']) && $request_data['ended_at']!="" ? $request_data['ended_at'] : null;
        if($request_data['started_at'] && $request_data['ended_at']){
            if(strtotime($request_data['ended_at']) < strtotime($request_data['started_at'])){
                return $this->failedResponse(['message'=>[trans('event.started_at_after_ended_at')]]);
            }
        }

        $event = $this->eventRepository->create($request_data);
        $products = $request->input('condition_products',[]);
        $product_ids = [];
        foreach ($products as $key => $product) {
            $data = [
                'quantity'=>isset($product['quantity'])? $product['quantity'] : 0,
                'discount'=>isset($product['discount'])? $product['discount'] : 0,
            ];
            $product_ids[$product['id']] = $data;
        }
        $event->condition_products()->sync($product_ids);
        foreach ($product_ids as $product_id => $product_pivot) {
            $event->condition_products()->updateExistingPivot($product_id, $product_pivot);
        }

        $products = $request->input('products',[]);
        $product_ids = [];
        foreach ($products as $key => $product) {
            $data = [
                'quantity'=>isset($product['quantity'])? $product['quantity'] : 0,
                'discount'=>isset($product['discount'])? $product['discount'] : 0,
            ];
            $product_ids[$product['id']] = $data;
        }
        $event->products()->sync($product_ids);
        foreach ($product_ids as $product_id => $product_pivot) {
            $event->products()->updateExistingPivot($product_id, $product_pivot);
        }

        return $this->successResponse($event?$event:[]);
    }

    public function show(Request $request, $id)
    {
        
        $event = $this->eventRepository->getWith($id,[],['status'=>1]);

        return $this->successResponse($event?$event:[]);
    }

    public function edit($id)
    {
      
    }

    public function update(Request $request, $id)
    {
        $validator = $this->eventUpdateValidator($request->all(), $id);
        if($validator->fails()){
            return $this->validateErrorResponse($validator->errors()->all());
        }
       
        $request_data = $request->only(['name', 'started_at', 'ended_at', 'type', 'status']);
        $request_data['started_at'] = isset($request_data['started_at']) && $request_data['started_at']!="" ? $request_data['started_at'] : null;
        $request_data['ended_at'] = isset($request_data['ended_at']) && $request_data['ended_at']!="" ? $request_data['ended_at'] : null;
        if($request_data['started_at'] && $request_data['ended_at']){
            if(strtotime($request_data['ended_at']) < strtotime($request_data['started_at'])){
                return $this->failedResponse(['message'=>[trans('event.started_at_after_ended_at')]]);
            }
        }

        $event = $this->eventRepository->update($id, $request_data);
        $products = $request->input('condition_products',[]);
        $product_ids = [];
        foreach ($products as $key => $product) {
            $data = [
                'quantity'=>isset($product['quantity']) && $product['quantity']!=''? $product['quantity'] : 0,
                'discount'=>isset($product['discount']) && $product['discount']!=''? $product['discount'] : 0,
            ];
            $product_ids[$product['id']] = $data;
        }
        $event->condition_products()->sync($product_ids);

        $products = $request->input('products',[]);
        $product_ids = [];
        foreach ($products as $key => $product) {
            $data = [
                'quantity'=>isset($product['quantity'])? $product['quantity'] : 0,
                'discount'=>isset($product['discount'])? $product['discount'] : 0,
            ];
            $product_ids[$product['id']] = $data;
        }
        $event->products()->sync($product_ids);

        return $this->successResponse($event?$event:[]);
    }

    public function destroy(Request $request, $id)
    {
        $this->eventRepository->delete($id);
        return $this->successResponse(['id'=>$id]);

    }

    protected function eventValidator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'type' => 'numeric',
            'status' => 'numeric',
            'started_at'=>'nullable|date',
            'ended_at'=>'nullable|date',
        ]);        
    }

    protected function eventUpdateValidator(array $data, $id)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'type' => 'numeric',
            'status' => 'numeric',
            'started_at'=>'nullable|date',
            'ended_at'=>'nullable|date',
        ]);        
    }
}
