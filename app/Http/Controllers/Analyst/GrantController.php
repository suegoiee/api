<?php
namespace App\Http\Controllers\Analyst;
use App\Repositories\OrderRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
class GrantController extends AnalystController
{	
    public function __construct(Request $request, OrderRepository $orderRepository)
    {
        parent::__construct($request);
        $this->moduleName = 'order';
        $this->moduleRepository = $orderRepository;

        //$this->token = $this->clientCredentialsGrantToken();
    }

    public function index(Request $request)
    {
        $user = $request->user();
        $grants =$user->grants()->orderBy('year_month','DESC')->get();
        $query_string=[];
        $data = [
            'module_name'=> $this->moduleName,
            'subtitle'=>'grant',
            'actions'=>[],
            'tools'=>[],
            'tabs'=>[],
            'query_string' => $query_string,
            'table_data' =>$grants,
            'table_head' =>['statement_no', 'year_month'],
            'table_formatter' =>['statement_no'],
            'table_action'=>false,
        ];
        return view('analyst.list',$data);
    }
    public function show(Request $request, $id)
    {
        
        $user = $request->user();
        $grant =$user->grants()->find($id);
        if(!$grant){
            return redirect()->back();
        }
        $trans_price = $grant->price - $grant->handle_fee - $grant->platform_fee - $grant->income_tax - $grant->second_generation_nhi - $grant->interbank_remittance_fee;
        foreach ($grant->details as $key => $detail) {
            $trans_price -= $detail->amount;
        }
        $data = [
            'module_name'=> $this->moduleName,
            'subtitle'=>'grant',
            'grant'=>$grant,
            'user'=>$user,
            'trans_price'=>$trans_price,
        ];
        return view('analyst.grant',$data);
    }
    private function getHandleFee($paymentType, $price, $date){
        switch ($paymentType) {
            case '':return 0;
            case 'credit':return ($price*0.0275<5) ? 5:( $price * 0.0275);
            case 'webatm':case 'atm':return ($price*0.01<5) ? 5:(($price*0.01>26)?26:($price*0.01));
            case 'cvs':return strtotime($date) < strtotime('2018-09-01')? 26:30;
            default:return 0;
        }
    }
}
