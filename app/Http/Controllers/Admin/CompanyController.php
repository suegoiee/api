<?php
namespace App\Http\Controllers\Admin;
use App\Repositories\StockRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Lang;

class CompanyController extends AdminController
{	
    protected $stockRepository;

    public function __construct(Request $request, StockRepository $stockRepository)
    {
        parent::__construct($request);
        $this->moduleName='company';
        $this->moduleRepository = $stockRepository;

        //$this->token = $this->clientCredentialsGrantToken();
    }

    public function index()
    {
        $company = $this->moduleRepository->getsWith(['events'],[],['updated_at'=>'DESC','stock_code'=>'ASC']);
        $data = [
            'actionName'=>__FUNCTION__,
            'module_name'=> $this->moduleName,
            'actions'=>['new'],
            'table_data' => $company,
            'table_head' =>['stock_code','stock_name','stock_industries','industries','type'],
            'table_formatter' =>['stock_industries'],
        ];
        return view('admin.list',$data);
    }

    public function create()
    {
        $data = [
            'actionName'=>__FUNCTION__,
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
            'actionName'=>__FUNCTION__,
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

    public function export(Request $request)
    {
        $where['no.in'] = $request->ids;
        $data = $this->moduleRepository->getsWith(['events'], $where, ['updated_at'=>'DESC','stock_code'=>'ASC'])->toArray();
        $sheet = [];
        $company = ['代號' => null, '公司名稱' => null, '產業代碼' => null, '產業名稱' => null, '類型' => null];
        foreach ($data as $row) {
            foreach ($row as $key => $value) {
                switch ($key) {
                    case "stock_code":
                        $company['代號'] = $value;
                        break;
                    case "stock_name":
                        $company['公司名稱'] = $value;
                        break;
                    case "stock_industries":
                        $company['產業代碼'] = Lang::get('company.admin.stock_industries_'.$value); 
                        break;
                    case "industries":
                        $company['產業名稱'] = $value;
                        break;
                    case "type":
                        $company['類型'] = $value;
                        break;
                }
            }
            array_push($sheet, $company);
            $company = array_fill_keys(array_keys($company), null);
        }
        $this->tableExport($sheet);
    }
}
