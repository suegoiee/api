<?php
namespace App\Http\Controllers\Admin;
use App\Repositories\PromocodeRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
class PromocodeController extends AdminController
{	
    protected $userRepository;
    public function __construct(PromocodeRepository $promocodeRepository, UserRepository $userRepository)
    {
        $this->moduleName='promocode';
        $this->moduleRepository = $promocodeRepository;
        $this->userRepository = $userRepository;

        $this->token = $this->clientCredentialsGrantToken();
    }

    public function index(Request $request)
    {
        $query_string=[];

        $promocodes = $this->moduleRepository->getsWith(['user']);

        $data = [
            'module_name'=> $this->moduleName,
            'actions'=>['import','new'],
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
            'module_name'=> $this->moduleName,
            'users' => $this->userRepository->gets(),
            'data' => null,
        ];
        return view('admin.form',$data);
    }

    public function edit($id)
    {

        $data = [
            'module_name'=> $this->moduleName,
            'users' => $this->userRepository->gets(),
            'data' => $this->moduleRepository->getWith($id,['user']),
        ];
        return view('admin.form',$data);
    }

    public function importView()
    {
        $data = ['module_name'=> $this->moduleName];
        return view('admin.import', $data);
    }

    public function import(Request $request)
    {
        $filepath = $request->file('promocodefile')->getRealPath();
        $file = fopen($filepath,"r");
        $result = ['success'=>0, 'errors'=>[]];
        if($file){
            $insertArray = [];
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
                    'deadline'=>$line[3]!=''?$line[3]:null
                ];
                array_push($insertArray, $lineData);
            }
            $resultInsert = $this->moduleRepository->insertArray($insertArray);
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
