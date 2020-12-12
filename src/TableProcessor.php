<?php namespace Irsyadulibad\DataTables;

use CodeIgniter\Config\Services;

class TableProcessor extends DataTableMethods
{

	protected $db;

	private $table;

	private $results;

	public function __construct($db, $table)
	{
		$this->request = Services::request();

		$this->fields = $db->getFieldNames($table);
		$this->db = $db->table($table);
		$this->table = $table;
	}

	public function make($make = true)
	{
		$this->doQuery();
		$results = $this->results();

		return $this->render($results, $make);
	}

	public function count()
	{
		return $this->db->countAllResults(false);
	}

	private function doQuery()
	{
		$search = $this->request->getGet('search');

		$this->totalRecords = $this->count();
		$this->filtering($search);
		$this->ordering();
		$this->limiting();
	}

	private function filtering($search)
	{
		$fields = $this->request->getGet('columns');
		$keyword = $search['value'] ?? '';

		if(is_null($fields)) return;

		$this->db->groupStart();

		for($i = 0; $i < count($fields); $i++) {
			$where = false;
			$field = $fields[$i]['data'];
			$searchable = $fields[$i]['searchable'];

			foreach($this->whereFields as $data) {
				$where = ($field == $data) ? true : false;
			}

			if($where || !$searchable) continue;

			if(array_key_exists($field, $this->aliases)){
				$field = $this->aliases[$field];
				($i < 1) ? $this->db->like($field, $keyword) : $this->db->orLike($field, $keyword);
			}else if(in_array($field, $this->fields)){
				($i < 1) ? $this->db->like($field, $keyword) : $this->db->orLike($field, $keyword);
			}else{
				continue;
			}

		}

		$this->db->groupEnd();

	}

	private function ordering()
	{
		$orderField = esc($this->request->getGet('order')[0]['column']) ?? "";
		$orderAD = esc($this->request->getGet('order')[0]['dir']) ?? "";
		$orderColumn = esc($this->request->getGet('columns')[$orderField]['data']) ?? "";

		$this->db->orderBy($orderColumn, $orderAD);
	}

	private function limiting()
	{
		$limit = $this->request->getGet('length', FILTER_SANITIZE_NUMBER_INT);
		$start = $this->request->getGet('start', FILTER_SANITIZE_NUMBER_INT);
		$this->db->limit($limit, $start);
	}

	private function results()
	{
		$result = $this->db->get();
		$this->filteredRecords = $result->resultID->num_rows;
		
		return DataProcessor::processResult($result);
	}

}
