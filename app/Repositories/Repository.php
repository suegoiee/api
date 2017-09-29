<?php
namespace App\Repositories;

class Repository
{
	protected $model;
	public function get($id){
		return $this->model->find($id);
	}
	public function getWith($id,$with=[]){
		return $this->model->with($with)->find($id);
	}
	public function getBy($where=[]){
		$query = $this->model;
		foreach ($where as $key => $value) {
			$query = $query->where($key,$value);
		}
		return $query->get();
	}
	public function gets(){
		return $this->model->get();
	}
	public function getsWith($with=[],$where=[]){
		$query = $this->model->with($with);
		foreach ($where as $key => $value) {
			$query = $query->where($key,$value);
		}
		return $query->get();
	}
	public function create($data){
		return $this->model->create($data);
	}
	public function update($id,$data){
		$this->model->where('id',$id)->update($data);
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
}