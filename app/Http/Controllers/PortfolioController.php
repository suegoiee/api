<?php

namespace App\Http\Controllers;
use App\Repositories\PortfolioRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PortfolioController extends Controller
{	
    protected $portfolioRepository;
    protected $productRepository;

    public function __construct(PortfolioRepository $portfolioRepository)
    {
	   $this->portfolioRepository = $portfolioRepository;
    }

    public function index(Request $request)
    {
        $user = $request->user();
        $laboratory_id = $request->input('laboratory',false);
        $single_id = $request->input('single',false);
        $where_portfolio = $user->portfolios()->with(['transactions']);
        if($laboratory_id){
            $where_portfolio = $where_portfolio->where('laboratory_id',$laboratory_id);
        }
        if($single_id){
            $where_portfolio = $where_portfolio->where('single_id',$single_id);
        }
        $portfolios = $where_portfolio->get()->makeHidden(['user_id', 'laboratory_id', 'single_id', 'updated_at', 'created_at']);
        foreach ($portfolios as $key => $portfolio) {
           $portfolio->transactions->makeHidden(['portfolio_id','updated_at']);
        }
        return $this->successResponse($portfolios);
    }

    public function store(Request $request)
    {
        $user = $request->user();
        $laboratory_id = $request->input('laboratory');
        $single_id = $request->input('single');

        $validator = $this->portfolioValidator($request->all(), $user->id);
        if($validator->fails()){
            return $this->validateErrorResponse($validator->errors()->all());
        }

        $request_data = $request->only(['stock_code', 'cheap_price', 'expensive_price']);
        if($user->portfolios()->where('stock_code', $request_data['stock_code'])->count()){
            return $this->validateErrorResponse([trans('portfolio.stock_exists')]);
        }
        $request_data['laboratory_id'] = $laboratory_id;
        $request_data['single_id'] = $single_id;
        $portfolio = $user->portfolios()->create($request_data);

        $transactions = $request->input('transactions',[]);

        foreach ($transactions as $key => $transaction) {
            $data = ['price'=>$transaction['price'], 'quantity' => $transaction['quantity']];
            $portfolio->transactions()->create($data);
        }
        $portfolio->transactions = $portfolio->transactions->makeHidden(['portfolio_id','updated_at']);
        $portfolio->makeHidden(['user_id', 'updated_at', 'created_at']);

        return $this->successResponse($portfolio);
    }

    public function update(Request $request, $id)
    {
        $user = $request->user();

        $validator = $this->portfolioValidator($request->all(), $user->id);
        if($validator->fails()){
            return $this->validateErrorResponse($validator->errors()->all());
        }

        $request_data = $request->only(['cheap_price', 'expensive_price']);
        
        $portfolio = $user->portfolios()->where('id',$id)->first();
        if($portfolio){
            $portfolio->update($request_data);
        }

        $transactions = $request->input('transactions',[]);
        $deleted_ids = [];
        foreach ($transactions as $key => $transaction) {
            $data = ['price'=>$transaction['price'], 'quantity' => $transaction['quantity']];
            if(isset($transaction['id'])){
                $portfolio->transactions()->where('id', $transaction['id'])->update($data);
                array_push($deleted_ids, $transaction['id']);
            }else{
                $transaction_data = $portfolio->transactions()->create($data);
                array_push($deleted_ids, $transaction_data->id);
            }
        }
        $portfolio->transactions()->whereNotIn('id', $deleted_ids)->delete();

        $portfolio->transactions = $portfolio->transactions()->get();
        $portfolio->transactions->makeHidden(['portfolio_id','updated_at']);
        $portfolio->makeHidden(['user_id', 'laboratory_id', 'single_id', 'updated_at', 'created_at']);

        return $this->successResponse($portfolio);
    }

    public function destroy(Request $request, $id = 0)
    {

        $user = $request->user();
        $ids = $id ? [$id] : $request->input('portfolios', []);
        
        foreach ($ids as $key => $id) {
            $portfolio = $user->portfolios()->find($id);
            if($portfolio){
                $user->portfolios()->where('id',$id)->delete();
            }
        }
        if(count($ids)==0){
            return $this->successResponse(['message'=>['No Portfolio was deleted.'], 'deleted'=>count($ids)]);
        }

        return $this->successResponse(['message'=>['Portfolios was deleted.'], 'deleted'=>count($ids)]);
    }

    protected function portfolioValidator(array $data , $user_id)
    {
        return Validator::make($data, [
            'stock_code' => 'max:255',
            'cheap_price'=>'numeric',
            'expensive_price'=>'numeric',
            'transactions.*.price'=>'numeric',
            'transactions.*.quantity'=>'numeric',
        ]);        
    }
}
