<?php namespace Irsyadulibad\DataTables;

use CodeIgniter\Config\Services;
use Irsyadulibad\DataTables\Utilities\Request;

class TableProcessor extends DataTableMethods
{

	protected $db;

	private $table;

	private $results;

	public function __construct($db, $table)
	{
		$this->request = new Request;

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
		$this->totalRecords = $this->count();
		$this->filtering();
		$this->filterRecords();
		$this->ordering();
		$this->limiting();
	}

	private function filtering()
	{
		$fields = $this->request->getColumns();
		$keyword = $this->request->getKeyword();

		if(is_null($keyword)) return;

		$this->db->groupStart();

		for($i = 0; $i < count($fields); $i++) {
			$where = false;
			$field = $fields[$i]['data'];
			$searchable = $fields[$i]['searchable'];

			if(!$searchable) continue;

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

		$this->isFilterApplied = true;
	}

	private function ordering()
	{
		$order = $this->request->getOrdering();
		$column = $order['column'];

		if(!array_key_exists($column, $this->aliases) && !in_array($column, $this->fields))
			return;

		$this->db->orderBy($column, $order['sort']);
	}

	private function limiting()
	{
		$req = $this->request->getLimiting();

		$this->db->limit($req['limit'], $req['offset']);
	}

	private function results()
	{
		$result = $this->db->get();

		$processor = new DataProcessor(
			$result,
			$this->processColumn
		);
		
		return $processor->process();
	}

}
