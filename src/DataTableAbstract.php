<?php

namespace Irsyadulibad\DataTables;

use CodeIgniter\Database\BaseBuilder;
use CodeIgniter\Format\JSONFormatter;

abstract class DataTableAbstract
{
    /**
     * Column to be processed
     */
    protected array $columnDef = [
        'index' => [
            'indexed' => false,
            'rowID' => 'DT_RowIndex'
        ],
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

    protected BaseBuilder $builder;

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
     * Add indexed column to JSON response
     * 
     * @param string $rowid | row index name
     */
    public function addIndexColumn(string $rowid = null)
    {
        $this->columnDef['index']['indexed'] = true;

        if(!is_null($rowid)) $this->columnDef['index']['rowID'] = $rowid;
        return $this;
    }

    /**
     * Render response data
     * 
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
}
