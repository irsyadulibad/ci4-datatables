<?php

namespace Irsyadulibad\DataTables\Traits;

use CodeIgniter\Database\BaseBuilder;

trait QueryMethods
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
