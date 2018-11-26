<?php
namespace App\Http\Controllers\Admin;
use App\Repositories\PromocodeRepository;
use App\Repositories\ProductRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Route;
class PromocodeController extends AdminController
{	
    protected $userRepository;
    protected $productRepository;
    public function __construct(Request $request, PromocodeRepository $promocodeRepository, UserRepository $userRepository,ProductRepository $productRepository)
    {
        parent::__construct($request);
        $this->moduleName='promocode';
        $this->moduleRepository = $promocodeRepository;
        $this->userRepository = $userRepository;
        $this->productRepository = $productRepository;
        //$this->token = $this->clientCredentialsGrantToken();
    }

    public function index(Request $request)
    {
        $query_string=[];
        if($request->has('type')){
            $where['type'] = $request->input('type',0);
            $query_string = $request->only(['type']);
        }else{
            $where['type'] = $request->input('type',0);
            $query_string['type'] = $request->input('type',0);
        }
        $promocodes = $this->moduleRepository->getsWith(['user','user.profile','used'], $where,['updated_at'=>'DESC']);

        $data = [
            'actionName'=>__FUNCTION__,
            'module_name'=> $this->moduleName,
            'actions'=>['import','new'],
            'tabs'=>['type'=>[0,1]],
            'query_string' => $query_string,
            'table_data' => $promocodes,
            'table_head' =>['name','code','offer','user_name','deadline', 'used_at'],
            'table_formatter' =>['status', 'user_name', 'deadline', 'used_at'],
        ];
        return view('admin.list',$data);
    }

    public function create()
    {
        $data = [
            'actionName'=>__FUNCTION__,
            'module_name'=> $this->moduleName,
            'users' => $this->userRepository->getsWith(['profile']),
            'products'=>$this->productRepository->getsWith([],[],['status'=>'DESC','updated_at'=>'DESC']),
            'data' => null,
        ];
        return view('admin.form',$data);
    }

    public function edit($id)
    {

        $data = [
            'actionName'=>__FUNCTION__,
            'module_name'=> $this->moduleName,
            'users' => $this->userRepository->getsWith(['profile']),
            'data' => $this->moduleRepository->getWith($id,['user']),
            'products'=>$this->productRepository->getsWith([],[],['status'=>'DESC','updated_at'=>'DESC']),
        ];
        return view('admin.form',$data);
    }

    public function importView()
    {
        $data = [
            'actionName'=>__FUNCTION__,
            'module_name'=> $this->moduleName];
        return view('admin.import', $data);
    }

    public function import(Request $request)
    {
        $filepath = $request->file('promocodefile')->getRealPath();
        $file = fopen($filepath,"r");
        $result = ['success'=>0, 'errors'=>[]];
        if($file){
            $insertArray = [];
            $promocodes = [];
            $ignoreLine = true;
            while (($line = fgetcsv($file)) !== FALSE) {
                if($ignoreLine){
                    $ignoreLine = false;
                    continue;
                }
                $lineData=[
                    'name'=>$line[0],
                    'code'=>$line[1],
                    'offer'=>$line[2],
                    'deadline'=>$line[3]!=''?$line[3]:null,
                    'type'=>$line[4],
                    'user_id'=>$line[5]!='' ? $line[5]:0,
                    'specific'=>$line[6]
                ];
                $promocodes[$line[1]]=$lineData;
                $promocodes[$line[1]]['products']=[];

                foreach ($line as $key => $col) {
                    if($key<=6){
                        continue;
                    }
                    if($col != '' && $this->productRepository->get($col)){
                        
                        array_push($promocodes[$line[1]]['products'], $col);
                    }
                }
                array_push($insertArray, $lineData);
            }
            $resultInsert = $this->moduleRepository->insertArray($insertArray);
            foreach ($resultInsert['data'] as $key => $data) {
                $data->products()->sync($promocodes[$data->code]['products']);
            }
            $result['success']= $resultInsert['success'];
            foreach ($resultInsert['errors'] as $key => $error) {
                array_push($result['errors'], $error->code.' '.trans('import.is_exist'));
            }
            fclose($file);
        }else{
            $result['errors']=[trans('import.file_error')];
        }
        return redirect()->back()->with($result);
        //$content = file_get_contents($request->file('promocodefile')->getRealPath());
    }
}
