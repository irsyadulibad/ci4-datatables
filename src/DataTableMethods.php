<?php namespace Irsyadulibad\DataTables;

use CodeIgniter\Format\JSONFormatter;

abstract class DataTableMethods
{
	protected $tables = [];

	protected $fields = [];

	protected $aliases = [];

	protected $totalRecords;

	protected $filteredRecords;

	protected $isFilterApplied = false;

	protected $processColumn = [
		'appends' => [],
		'hidden' => [],
		'raws' => [],
		'edit' => []
	];

	public function select(String $fields)
	{
		$this->builder->select($fields);

		$this->setAliases($fields);
		return $this;
	}

	public function where(Array $data)
	{
		$this->builder->where($data);

		return $this;
	}

	public function join($table, $cond, $type = '')
	{
		$this->addTable($table);
		$this->builder->join($table, $cond, $type);

		return $this;
	}

	public function hideColumns(Array $cols)
	{
		$this->processColumn['hidden'] = $cols;

		return $this;
	}

	public function rawColumns(Array $cols)
	{
		$this->processColumn['raws'] = $cols;

		return $this;
	}

	public function addColumn(String $name, $callback)
	{
		$this->processColumn['appends'][] = [
			'name' => $name,
			'callback' => $callback
		];

		return $this;
	}

	public function editColumn(String $name, $callback)
	{
		$this->processColumn['edit'][] = [
			'name' => $name,
			'callback' => $callback
		];

		return $this;
	}

	protected function render($results, $make)
	{
		$formatter = new JSONFormatter;

		$output = [
			'draw' => $this->request->getDraw(),
			'recordsTotal' => $this->totalRecords,
			'recordsFiltered' => $this->filteredRecords,
			'data' => $results
		];

		if($make) return $formatter->format($output);
		return d($output);
	}

	protected function filterRecords()
	{
		if($this->isFilterApplied) {
			$this->filteredRecords = $this->count();
		} else {
			$this->filteredRecords = $this->totalRecords;
		}

	}

	private function setAliases($fields)
	{
		foreach(explode(',', $fields) as $val) {
			if(stripos($val, 'as')) {
				$alias = trim(preg_replace('/(.*)\s+as\s+(\w*)/i', '$2', $val));
				$field = trim(preg_replace('/(.*)\s+as\s+(\w*)/i', '$1', $val));

				$this->aliases[$alias] = $field;
			}
		}

		return true;
	}

	private function addTable($table) {
		if(stripos($table, 'as')) {
			$table = trim(preg_replace('/(.*)\s+as\s+(\w*)/i', '$1', $table));
		}

		$this->tables[] = $table;
	}
}
