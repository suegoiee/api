<?php
namespace App\Repositories;

class Repository
{
	protected $model;
	protected $condition;
	protected $uniqueKey;
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
	public function getsWith($with=[],$where=[],$order=[], $whereHas=[]){
		$query = $this->model->with($with);
		foreach ($where as $key => $value) {
			$field_array = explode('.', $key);
			if(count($field_array)>1){
				if($field_array[1] == 'in'){
					$query = $query->whereIn($field_array[0], $value);
				}else{
					$query = $query->where($field_array[0], $field_array[1], $value);
				}
			}else{
				$query = $query->where($key,$value);
			}
		}
		foreach ($order as $key => $value) {
			$query = $query->orderBy($key,$value);
		}
		foreach ($whereHas as $key => $value) {
			$query = $query->whereHas($key, $value);
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

	public function insertArray($array=[]){
		$result = ['success'=>0,'errors'=>[],'data'=>[]];
		foreach ($array as $data) {
			$modelData = $this->model->where($this->uniqueKey, $data[$this->uniqueKey])->first();
			if(!$modelData){
				$this->model->insert($data);
				$modelData = $this->model->where($this->uniqueKey, $data[$this->uniqueKey])->first();
				array_push($result['data'], $modelData);
				$result['success']++;
			}else{
				array_push($result['errors'], $modelData);
			}
        }
        return $result;
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
	public function limit($offset=0, $limit=100){
        $this->condition = $this->condition->offset($offset)->limit($limit);
        return $this; 
    }
	public function whereBy($params=[]){
        foreach ($params as $key => $value) {
            $column = explode('.',$key);
            if(count($column)>1){
                $this->condition = $this->condition->where($column[0], $column[1], $value);
            }else{
                $this->condition = $this->condition->where($key,$value);
            }
        }
        return $this;
    }
    public function orderBy($params=[]){
        foreach ($params as $key => $value) {
            $orderBy_array = explode('.', $key);
            if(count($orderBy_array)>2){
                $rtable = $orderBy_array[0].'.'.$orderBy_array[1];
                $rcolumn = $orderBy_array[2];
                $this->condition = $this->condition->load([$rtable => function($query) use ($rcolumn, $value){
                    $query->orderBy($rcolumn, $value);
                }]);
            }else if(count($orderBy_array)>1){
                $rtable = $orderBy_array[0];
                $rcolumn = $orderBy_array[1];
                $this->condition = $this->condition->load([$rtable => function($query) use ($rcolumn, $value){
                    $query->orderBy($rcolumn, $value);
                }]);
            }else{
                $this->condition = $this->condition->orderBy($key, $value);
            }
        }
        return $this;
    }
    public function whereHas($query,$relation_column,$value){
        $self = $this;
        $whereHas_array = explode('.', $relation_column,2);
        if(count($whereHas_array)>1){
            $relation_table = $whereHas_array[0];
            $relation_column = $whereHas_array[1];
            $query->whereHas($relation_table,function ($query2) use ($relation_column, $value, $self) {
                $self->whereHas($query2, $relation_column, $value);
            });
        }else{
            $query->where($relation_column, 'like', '%'.$value.'%');
        }
        return $query;
    }
    public function searchBy($columns=[], $searchText='',$orWhereHas=[]){
    	if($searchText==''){
    		return $this;
    	}
        $self = $this;
        foreach ($orWhereHas as $key => $value) {
            $orWhereHas_array = explode('.', $value,2);
            $relation_table = $orWhereHas_array[0];
            $relation_column = $orWhereHas_array[1];
            $this->condition =  $this->condition->orWhereHas($relation_table, function ($query) use ($relation_column, $searchText, $self) {
                    $self->whereHas($query,$relation_column,$searchText);
                });
        }
        $this->condition = $this->condition->orWhere(function($query) use ($columns, $searchText){
            foreach ($columns as $key => $value) {
                $query->orWhere($value,'like','%'.$searchText.'%');
            }
        });

        return $this;
    }
    public function toWith($with=false){
        if($with){
            $this->condition = $this->condition->with($with);
        }
        return $this;
    }
    public function toGets(){
        $result = $this->condition->get();
        $this->condition = $this->model;
        return $result; 
    }
    public function toDelete(){
        $result = $this->condition->delete();
        $this->condition = $this->model;
        return $result; 
    }
    public function toGet(){
        $result = $this->condition->first();
        $this->condition = $this->model;
        return $result; 
    }
    public function toCount(){
        $result = $this->condition->count();
        $this->condition = $this->model;
        return $result; 
    }
	public function model(){
		return $this->model;
	}
	public function count(){
		return $this->model->count();
	}
}