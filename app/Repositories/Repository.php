<?php
namespace App\Repositories;

class Repository
{
	protected $model;
	public function makeHidden($attribute){
		$this->model = $this->model->makeHidden($attribute);
	}
	public function get($id){
		return $this->model->find($id);
	}
	public function getWith($id,$with=[]){
		return $this->model->with($with)->find($id);
	}
	public function getBy($where=[], $with=[]){
		$query = $this->model->with($with);
		foreach ($where as $key => $value) {
			$field_array = explode('.', $key);
			if(count($field_array)>1){ 
				$query = $query->where($field_array[0], $field_array[1], $value);
			}else{
				$query = $query->where($key,$value);
			}
		}
		return $query->orderBy('created_at','DESC')->first();
	}
	public function gets($select=false){
		if($select){
			return $this->model->get($select);
		}else{
			return $this->model->get();
		}
	}
	public function getsWith($with=[],$where=[],$order=[]){
		$query = $this->model->with($with);
		foreach ($where as $key => $value) {
			$field_array = explode('.', $key);
			if(count($field_array)>1){ 
				$query = $query->where($field_array[0], $field_array[1], $value);
			}else{
				$query = $query->where($key,$value);
			}
		}
		foreach ($order as $key => $value) {
			$query = $query->orderBy($key,$value);
		}
		return $query->get();
	}
	public function getsWithPaginate($with=[],$where=[],$order=[], $paginate = 10){
        $query = $this->model->with($with);
        foreach ($where as $key => $value) {
            $field_array = explode('.', $key);
            if(count($field_array)>1){
                $query = $query->where($field_array[0], $field_array[1], $value);
            }else{
                $query = $query->where($key,$value);
            }
        }
        foreach ($order as $key => $value) {
            $query = $query->orderBy($key,$value);
        }
        return $query->paginate($paginate);
	}
	public function create($data){
		return $this->model->create($data);
	}
	public function update($id,$data){
		$this->model->where($this->model->getKeyName(),$id)->update($data);
		return $this->model->find($id);
	}
	public function delete($id){
		$model = $this->model->find($id);
		$this->model->destroy($id);
		return $model;
	}
	public function model(){
		return $this->model;
	}
	public function count(){
		return $this->model->count();
	}
}