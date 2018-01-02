<?php
namespace App\Http\Controllers\Admin;
use App\Repositories\StockRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
class CompanyController extends AdminController
{	
    protected $stockRepository;

    public function __construct(StockRepository $stockRepository)
    {
        $this->moduleName='company';
        $this->moduleRepository = $stockRepository;

        $this->token = $this->clientCredentialsGrantToken();
    }

    public function index()
    {
        $company = $this->moduleRepository->getsWith(['events'],[],['updated_at'=>'DESC','stock_code'=>'ASC']);
        $data = [
            'module_name'=> $this->moduleName,
            'actions'=>['new'],
            'table_data' => $company,
            'table_head' =>['stock_code','stock_name','stock_industries','type'],
            'table_formatter' =>[],
        ];
        return view('admin.list',$data);
    }

    public function create()
    {
        $data = [
            'module_name'=> $this->moduleName,
            'data'=>null,
            'industries'=> $this->moduleRepository->getIndustreis(),
        ];
        return view('admin.form',$data);
    }

    public function edit($id)
    {
        $company = $this->moduleRepository->getWith($id,['events']);
        $company->id = $company->no;
        $data = [
            'module_name'=> $this->moduleName,
            'data' => $company,
            'industries'=> $this->moduleRepository->getIndustreis(),
        ];
        return view('admin.form',$data);
    }
    public function store(Request $request)
    {
        $http = new \GuzzleHttp\Client;
        $response = $http->request('post',url('/'.str_plural($this->moduleName)),[
                'headers'=>[
                    'Accept' => 'application/json',
                    'Authorization' => 'Bearer '.$this->token['access_token'],
                ],
                'form_params' => $request->all(),
            ]);
        $response_stock = json_decode((string) $response->getBody(), true);
        if($response_stock['status']!='error'){
            $response_stock['data']['id'] = $response_stock['data']['no'];
        }
        return $this->adminResponse($request,$response_stock);
    }

    public function update(Request $request, $id)
    {
        $http = new \GuzzleHttp\Client;
        $response = $http->request('put',url('/'.str_plural($this->moduleName).'/'.$id),[
                'headers'=>[
                    'Accept' => 'application/json',
                    'Authorization' => 'Bearer '.$this->token['access_token'],
                ],
                'form_params' => $request->all(),
            ]);
        $response_stock = json_decode((string) $response->getBody(), true);
        if($response_stock['status']!='error'){
            $response_stock['data']['id'] = $response_stock['data']['no'];
        }
        return $this->adminResponse($request,$response_stock );
    }
}
