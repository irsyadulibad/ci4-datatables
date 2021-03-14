<?php namespace Irsyadulibad\DataTables;

use CodeIgniter\Config\Services;
use Irsyadulibad\DataTables\Utilities\Request;

class TableProcessor extends DataTableMethods
{

	protected $builder;

	private $db;

	private $table;

	public function __construct($db, $table)
	{
		$this->request = new Request;

		$this->tables[] = $table;
		$this->db = $db;
		$this->builder = $db->table($table);
	}

	public function make($make = true)
	{
		$this->setListFields();
		$this->doQuery();
		$results = $this->results();

		return $this->render($results, $make);
	}

	public function count()
	{
		return $this->builder->countAllResults(false);
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

		$this->builder->groupStart();

		for($i = 0; $i < count($fields); $i++) {
			$where = false;
			$field = $fields[$i]['data'];
			$searchable = $fields[$i]['searchable'];

			if(!$searchable) continue;

			if(array_key_exists($field, $this->aliases)){
				$field = $this->aliases[$field];
				($i < 1) ? $this->builder->like($field, $keyword) : $this->builder->orLike($field, $keyword);
			}else if(in_array($field, $this->fields)){
				$mainTable = $this->tables[0];
				($i < 1) ? $this->builder->like("$mainTable.$field", $keyword) : $this->builder->orLike("$mainTable.$field", $keyword);
			}else{
				continue;
			}
		}

		$this->builder->groupEnd();

		$this->isFilterApplied = true;
	}

	private function ordering()
	{
		$order = $this->request->getOrdering();
		$column = $order['column'];

		if(!array_key_exists($column, $this->aliases) && !in_array($column, $this->fields))
			return;

		$this->builder->orderBy($column, $order['sort']);
	}

	private function limiting()
	{
		$req = $this->request->getLimiting();

		$this->builder->limit($req['limit'], $req['offset']);
	}

	private function setListFields() {
		foreach($this->tables as $table) {
			$fields = $this->db->getFieldNames($table);

			$this->fields = array_merge($this->fields, $fields);
		}
	}

	private function results()
	{
		$result = $this->builder->get();

		$processor = new DataProcessor(
			$result,
			$this->processColumn
		);
		
		return $processor->process();
	}

}
