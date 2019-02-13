<?php
namespace App\Http\Controllers\Admin;
use App\Repositories\PromocodeRepository;
use App\Repositories\ProductRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Route;
use Session;
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
        if($where['type']==0){
            $table_head = ['name','code','offer','deadline', 'used_at'];
            $table_formatter = ['status', 'deadline', 'used_at'];
        }else{
            $table_head = ['name','code','offer','user_name','deadline', 'used_at'];
            $table_formatter = ['status', 'user_name', 'deadline', 'used_at'];
        }
        $data = [
            'actionName'=>__FUNCTION__,
            'module_name'=> $this->moduleName,
            'actions'=>['import','new'],
            'tabs'=>['type'=>[0,1]],
            'query_string' => $query_string,
            'table_data' => $promocodes,
            'table_head' =>$table_head,
            'table_formatter' =>$table_formatter,
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
        $promocode =  $this->moduleRepository->getWith($id,['user']);
        if($promocode->type == 0){
           $promocode->used_at = $promocode->used->count()>0 ? date('Y-m-d H:i:s'):null;
        }
        $data = [
            'actionName'=>__FUNCTION__,
            'module_name'=> $this->moduleName,
            'users' => $this->userRepository->getsWith(['profile']),
            'data' => $promocode,
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
        $now = date('Y-m-d H:i:s');
        $filepath = $request->file('promocodefile')->getRealPath();
        $file = fopen($filepath,"r");
        $result = ['success'=>0, 'errors'=>[],'infos'=>[]];
        $rows = 0;
        if($file){
            $insertArray = [];
            $promocodes = [];
            $ignoreLine = 2;
            while (($line = fgetcsv($file)) !== FALSE) {
                if($ignoreLine>0){
                    $ignoreLine--;
                    continue;
                }
                $lineData=[
                    'name'=>$line[0],
                    'code'=>$line[1],
                    'offer'=>$line[2],
                    'deadline'=>$line[3]!=''?$line[3]:null,
                    'type'=>$line[4],
                    'user_id'=>$line[5]!='' ? $line[5]:0,
                    'specific'=>$line[6],
                    'retrict_type'=>$line[7],
                    'retrict_condition'=>$line[8],
                    'times_limit'=>$line[9],
                    'disabled'=>$line[10],
                    'created_at'=>$now,
                    'updated_at'=>$now,
                ];
                $promocodes[$line[1]]=$lineData;
                $promocodes[$line[1]]['products']=[];

                foreach ($line as $key => $col) {
                    if($key<=10){
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
        if(count($result['errors'])>0){
            Session::flash('infos', $result);
        }
        return redirect()->back();
        //$content = file_get_contents($request->file('promocodefile')->getRealPath());
    }

    public function export(Request $request)
    {
        $where['id.in'] = $request->ids;
        $data = $this->moduleRepository->getsWith(['user','user.profile','used'], $where,['updated_at'=>'DESC'])->toArray();
        $sheet = [];
        $promocode = ['名稱' => null, '優惠碼' => null, '折扣碼' => null, '擁有者' => null, '使用期限' => null, '已使用' => null];
        foreach ($data as $row) {
            foreach ($row as $key => $value) {
                switch ($key) {
                    case "name":
                        $promocode['名稱'] = $value;
                        break;
                    case "code":
                        $promocode['優惠碼'] = $value;
                        break;
                    case "offer":
                        $promocode['折扣碼'] = $value;
                        break;
                    case "user_name":
                        $promocode['擁有者'] = (empty($value)) ? '未分配' : $value;
                        break;
                    case "deadline":
                        $promocode['使用期限'] = (empty($value)) ? '無期限' : $value;
                        break;
                    case "used_at":
                        $promocode['已使用'] = (empty($value)) ? '0' : '1';
                        break;
                }
            }
            array_push($sheet, $promocode);
            $promocode = array_fill_keys(array_keys($promocode), null);
        }
        $this->tableExport($sheet);
    }
}
