<?php
namespace App\Http\Controllers\Admin;
use App\Repositories\TagRepository;
use App\Repositories\AnnouncementRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Lang;

class AnnouncementController extends AdminController
{	
    protected $tagRepository;

    public function __construct(Request $request, AnnouncementRepository $announcementRepository, TagRepository $tagRepository)
    {
        parent::__construct($request);
        $this->moduleName = 'announcement';
        $this->moduleRepository = $announcementRepository;
        $this->tagRepository = $tagRepository;
    }

    public function index(Request $request)
    {
        $query_string=[];
        $query_data = [];
        if($request->has('status')){
            $where['status'] = $request->input('status',1);
            $query_string = $request->only(['status']);
            $query_data['status'] = $where['status'];
        }else{
            $where['status'] = $request->input('status',1);
            $query_string['status'] = $request->input('status',1);
            $query_data['status'] = $where['status'];
        }
        $announcements = [];
        $data = [
            'actionName'=>__FUNCTION__,
            'module_name'=> $this->moduleName,
            'actions'=>['new'],
            'tabs'=>['status'=>[1,0]],
            'query_string' => $query_string,
            'query_data' => $query_data,
            'server_side' => 'active',
            'table_data' => $announcements,
            'table_head' =>['title','type','created_at'],
            'table_sortable' =>['title','type','created_at'],
            'table_formatter' =>[]
        ];
        return view('admin.list',$data);
    }

    public function data(Request $request)
    {
        $where=[];
        $orderBy=[];
        $with = [];
        $announcements_by = $this->moduleRepository;
        $search_fields = ['title', 'content'];
        $search_relation_fields = [];
        $search = ""; 
        $where['status'] = $request->input('status',1);

        if($request->has('sort')){
            $order_column = $request->input('sort', false);
            $order = $request->input('order');
            if($order_column){
                $orderBy[$order_column] = $order;
            }
        }else{
            $order_column = $request->input('sort');
            $order = $request->input('order');
            $orderBy['created_at'] = 'DESC';
        }

        $offset = $request->input('offset',0);
        $limit = $request->input('limit',100);

        if($request->has('search') && $request->input('search','')!=''){
            $search = $request->input('search','');
        }
        $announcements_total = $announcements_by->whereBy($where)->toCount();
        $announcements_total_filtered = $announcements_by->searchBy( $search_fields, $search, $search_relation_fields)->whereBy($where)->orderBy($orderBy)->toCount();

        $announcements = $announcements_by->toWith($with)->searchBy( $search_fields, $search, $search_relation_fields)->whereBy($where)->orderBy($orderBy)->limit($offset, $limit)->toGets();
  
        $data = [
            "total" => $announcements_total_filtered,
            "totalNotFiltered" => $announcements_total,
            "rows" => $announcements
        ];
        return response()->json($data);
    }
    public function create()
    {
        $data = [
            'actionName'=>__FUNCTION__,
            'module_name'=> $this->moduleName,
            'tags'=>$this->tagRepository->gets(),
            'data'=>null,
        ];
        return view('admin.form',$data);
    }
    public function  show(){

    }

    public function edit($id)
    {

        $data = [
            'actionName'=>__FUNCTION__,
            'module_name'=> $this->moduleName,
            'tags'=>$this->tagRepository->gets(),
            'data' => $this->moduleRepository->getWith($id),
        ];
        return view('admin.form',$data);
    }
    public function store(Request $request)
    {
        $validator = $this->announcementValidator($request->all());
        if($validator->fails()){
            return $this->adminFailedResponse($validator->errors()->first());
        }

        $request_data = $request->only(['title','content','type','status']);
        $announcement = $this->moduleRepository->create($request_data);
        
        return $this->adminResponse($request, ['status'=>'success','data'=>$announcement]);
    }

    public function update(Request $request, $id)
    {
        $validator = $this->announcementValidator($request->all());
        if($validator->fails()){
            return $this->adminFailedResponse($validator->errors()->first());
        }

        $request_data = $request->only(['title','content','type','status']);
        $announcement = $this->moduleRepository->update($id, $request_data);
        
        return $this->adminResponse($request, ['status'=>'success','data'=>$announcement]);
    }
    protected function announcementValidator(array $data)
    {
        return Validator::make($data, [
            'title' => 'required|max:255',
            'content' => 'string',
            'type'=> 'required|max:255',
            'status'=> 'required|in:0,1'
        ]);        
    }
}
