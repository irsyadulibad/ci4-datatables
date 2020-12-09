<?php namespace App\Libraries;
use \Config\Services;
use \Config\Database;

class Datatables{
	private $db;
	private $builder;
	private $request;
	private $table;
	private $alias = [];
	private $whereFields = [];
	private $whereData;
	private $joins = [];
	private $fields;

	public function __construct(){
		$this->request = Services::request();
	}

	public function table($table){
		$this->table = $table;
		$db = Database::connect();
		$this->db = $db;
		$this->builder = $db->table($table);
		$this->fields = $db->getFieldNames($table);
		return $this;
	}

	public function select(String $fields){
		$this->builder->select($fields);
		$this->setAlias($fields);
		return $this;
	}

	public function where(Array $data){
		$this->builder->where($data);
		foreach($data as $field => $value){
			$this->whereFields[] = $field;
		}
		$this->whereData = $data;
		return $this;
	}

	public function join($table, $cond, $type = ''){
		$this->joins[] = ['table' => $table, 'cond' => $cond, 'type' => $type];
		$this->builder->join($table, $cond, $type);
		return $this;
	}

	public function draw(){
		$keyword = esc($this->request->getPost('search')['value']);
		if(!is_null($keyword)) $this->getFiltering($keyword);
		$this->getOrdering();
		$result = $this->getResult();
		$paging = $this->getPaging($keyword);

		return json_encode([
			'draw' => $this->request->getPost('draw'),
			'recordsTotal' => $paging['total'],
			'recordsFiltered' => $paging['filtered'],
			'data' => $result
		]);
	}

	private function setAlias($data){
		foreach(explode(',', $data) as $val){
			if(stripos($val, 'as')){
				$alias = trim(preg_replace('/(.*)\s+as\s+(\w*)/i', '$2', $val));
				$field = trim(preg_replace('/(.*)\s+as\s+(\w*)/i', '$1', $val));
				$this->alias[$alias] = $field;
			}
		}
	}

	private function doJoin(){
		foreach($this->joins as $join){
			$this->builder->join($join['table'], $join['cond'], $join['type']);
		}
	}

	private function getFiltering($keyword){
		$fields = $this->request->getPost('columns');

		$this->builder->groupStart();

		for($i = 0; $i < count($fields); $i++){
			$where = false;
			$field = $fields[$i]['data'];
			$searchable = $fields[$i]['searchable'];

			foreach ($this->whereFields as $data) {
				$where = ($field == $data) ? true : false;
			}
			if($where || $searchable != 'true') continue;

			if(array_key_exists($field, $this->alias)){
				$field = $this->alias[$field];
				($i < 1) ? $this->builder->like($field, $keyword) : $this->builder->orLike($field, $keyword);
			}else if(in_array($field, $this->fields)){
				($i < 1) ? $this->builder->like($field, $keyword) : $this->builder->orLike($field, $keyword);
			}else{
				continue;
			}
		}

		$this->builder->groupEnd();
	}

	private function getOrdering(){
		$orderField = esc($this->request->getPost('order')[0]['column']) ?? "";
		$orderAD = esc($this->request->getPost('order')[0]['dir']) ?? "";
		$orderColumn = esc($this->request->getPost('columns')[$orderField]['data']) ?? "";
		$this->builder->orderBy($orderColumn, $orderAD);
	}

	private function getResult(){
		$this->getLimiting();
		return $this->builder->get()->getResultArray();
	}

	private function getLimiting(){
		$limit = $this->request->getPost('length', FILTER_SANITIZE_NUMBER_INT);
		$start = $this->request->getPost('start', FILTER_SANITIZE_NUMBER_INT);
		$this->builder->limit($limit, $start);
	}

	private function getPaging($keyword){
		if(count($this->joins) > 0) $this->doJoin();
		if(!is_null($this->whereData)) $this->where($this->whereData);
		$total = $this->builder->countAllResults(false);
		if(!is_null($keyword)) $this->getFiltering($keyword);
		return [
			'total' => $total,
			'filtered' => $this->builder->get()->resultID->num_rows
		];
	}
}
?>
