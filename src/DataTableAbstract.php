<?php

namespace Irsyadulibad\DataTables;

use CodeIgniter\Database\BaseBuilder;
use CodeIgniter\Format\JSONFormatter;

abstract class DataTableAbstract
{
    /**
     * List all queried table
     * 
     * @var array
     */
    protected array $tables = [];

    /**
     * List all of all table fields
     * 
     * @var array
     */
    protected array $fields = [];

    /**
     * List aliases columns
     * 
     * @var array
     */
    protected array $aliases = [];

    /**
     * Column to be processed
     */
    protected array $columnDef = [
        'index' => false,
        'append' => [],
        'edit' => [],
        'hide' => [],
        'raw' => []
    ];

    /**
     * Total all records
     * 
     * @var int
     */
    protected int $totalRecords = 0;

    /**
     * Total filtered records
     */
    protected int $filteredRecords;

    /**
     * Is filter record has been applied
     * 
     * @var bool
     */
    protected $isFilterApplied = false;

    /**
     * Builder object
     * 
     * @var \CodeIgniter\Database\BaseBuilder
     */
    protected BaseBuilder $builder;

    /**
     * Select the fields to be executed
     */
    public function select(string $fields)
    {
        $this->builder->select($fields);
        $this->setAliases($fields);

        return $this;
    }

    public function where($key, $value = null)
    {
        $this->builder->where($key, $value);
        return $this;
    }

    public function orWhere($key, $value = null)
    {
        $this->builder->orWhere($key, $value);
        return $this;
    }

    /**
     * Join table
     * 
     * @param string $table | table name
     * @param string $cond | join condition
     * @param string $type | join type: 'INNER', 'LEFT', 'RIGHT'
     */
    public function join(string $table, string $cond, string $type = 'INNER')
	{
		$this->addTable($table);
		$this->builder->join($table, $cond, $type);

		return $this;
	}

    /**
     * Hide column from response
     * 
     * @param array $cols | table columns
     */
    public function hideColumns(array $cols)
	{
		$this->columnDef['hide'] = $cols;
		return $this;
	}

    /**
     * Unescape column value output
     * 
     * @param array $cols | table columns
     */
	public function rawColumns(array $cols)
	{
		$this->columnDef['raw'] = $cols;
		return $this;
	}

    /**
     * Add custom column
     * 
     * @param string $name | column name
     * @param callable $callback | callback
     */
    public function addColumn(string $name, callable $callback)
    {
        $this->columnDef['append'][] = [
            'name' => $name,
            'callback' => $callback
        ];

        return $this;
    }

    /**
     * Editing existing column value
     * 
     * @param string $name | column name
     * @param callable $callback
     */
    public function editColumn(string $name, callable $callback)
    {
        $this->columnDef['edit'][] = [
            'name' => $name,
            'callback' => $callback
        ];

        return $this;
    }

    /**
     * Render response data
     * 
     * @
     */
    protected function render(bool $dump, array $results, int $draw)
    {
        $output = [
            'draw' => $draw,
            'recordsTotal' => $this->totalRecords,
            'recordsFiltered' => $this->filteredRecords,
            'data' => $results
        ];

        if(!$dump) {
            return (new JSONFormatter)->format($output);
        }

        return d($output);
    }

    /**
     * Count all total records
     */
    protected function countTotal()
    {
        return $this->builder->countAllResults(false);
    }

    /**
     * Count filtered records
     */
    protected function countFiltered()
    {
        if($this->isFilterApplied) {
            $this->filteredRecords = $this->countTotal();
            return;
        }

        $this->filteredRecords = $this->totalRecords;
    }

    /**
     * Store table alias to array
     * 
     * @param string $fields
     */
    private function setAliases(string $fields): void
	{
		foreach(explode(',', $fields) as $val) {
			if(stripos($val, 'as')) {
				$alias = trim(preg_replace('/(.*)\s+as\s+(\w*)/i', '$2', $val));
				$field = trim(preg_replace('/(.*)\s+as\s+(\w*)/i', '$1', $val));

				$this->aliases[$alias] = $field;
			}
		}
	}

    private function addTable(string $table) {
		if(stripos($table, 'as')) {
			$table = trim(preg_replace('/(.*)\s+as\s+(\w*)/i', '$1', $table));
		}

		$this->tables[] = $table;
	}
}
