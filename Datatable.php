<?php namespace App\Libraries;
use \Config\Services;
use \Config\Database;

class Datatable{
	private $db;
	private $builder;
	private $request;
	private $table;
	private $whereFields = [];
	private $whereData;

	public function __construct(){
		$this->request = Services::request();
	}

	public function table($table){
		$this->table = $table;
		$db = Database::connect();
		$this->db = $db;
		$this->builder = $db->table($table);
		return $this;
	}

	public function select(String $fields){
		$this->builder->select($fields);
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

	private function getFiltering($keyword){
		$fields = $this->db->getFieldNames($this->table);

		$this->builder->groupStart();
		$i = 0;
		foreach($fields as $field){
			$where = false;
			foreach ($this->whereFields as $data) {
				$where = ($field == $data) ? true : false;
			}
			if($where) continue;
			($i < 1) ? $this->builder->like($field, $keyword) : $this->builder->orLike($field, $keyword);
			$i++;
		}
		$this->builder->groupEnd();
	}

	private function getOrdering(){
		$orderField = esc($this->request->getPost('order')[0]['column']);
		$orderAD = esc($this->request->getPost('order')[0]['dir']);
		$orderColumn = esc($this->request->getPost('columns')[$orderField]['data']);
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
