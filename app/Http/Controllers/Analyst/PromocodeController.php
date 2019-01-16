<?php
namespace App\Http\Controllers\Analyst;
use App\Repositories\PromocodeRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
class PromocodeController extends AnalystController
{	
    public function __construct(Request $request, PromocodeRepository $promocodeRepository)
    {
        parent::__construct($request);
        $this->moduleName='promocode';
        $this->moduleRepository = $promocodeRepository;
    }

    public function index(Request $request)
    {
        $month = date('Y-m').'-01';
        $where= [
            
        ];
        $query_string=[];
        
        $user = $request->user();
        $products_ids = $user->products->map(function($item, $key){return $item->id;})->toArray();

        $promocodes = $this->moduleRepository->getsWith(['used', 'products'], $where, ['created_at'=>'DESC'], ['products'=>function($query) use ($products_ids){
                $query->whereIn('id',$products_ids);
            }]);

        $order_products = [];

        $data = [
            'module_name'=> $this->moduleName,
            'subtitle'=> 'promocode',
            'actions'=>[],
            'tools'=>[],
            'tabs'=>[],
            'query_string' => $query_string,
            'table_data' => $promocodes,
            'table_head' =>['code', 'name', 'offer', 'used','deadline'],
            'table_formatter' =>['used'],
            'table_sorter'=>[],
            'table_action'=>false,
        ];
        return view('analyst.list',$data);
    }
}
