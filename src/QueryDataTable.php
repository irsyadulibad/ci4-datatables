<?php

namespace Irsyadulibad\DataTables;

use CodeIgniter\Database\BaseConnection;
use Irsyadulibad\DataTables\Contracts\DataTableContract;
use Irsyadulibad\DataTables\Processors\DataProcessor;
use Irsyadulibad\DataTables\Traits\QueryMethods;
use Irsyadulibad\DataTables\Utilities\Request;

class QueryDataTable extends DataTableAbstract implements DataTableContract
{
    use QueryMethods;

    private BaseConnection $connection;

    public function __construct(BaseConnection $conn, string $table)
    {
        $this->builder = $conn->table($table);
        $this->tables[] = $table;
        $this->connection = $conn;
    }

    public function make(bool $dump = false)
    {
        $this->setListFields();
        $this->execQueries();
        
        $results = $this->results();
        return $this->render($dump, $results, Request::draw());
    }

    private function execQueries()
    {
        $this->totalRecords = $this->countTotal();
        $this->filterRecords();
        $this->countFiltered();
        $this->orderRecords();
        $this->limitRecords();
    }

    private function setListFields()
    {
        foreach($this->tables as $table) {
            $fields = $this->connection->getFieldNames($table);
            $this->fields = array_merge($this->fields, $fields);
        }
    }

    private function filterRecords(): void
    {
        $fields = Request::fields();
        $keyword = Request::keyword();

        if(!empty($fields)) {
            $this->builder->groupStart();
            $firstLike = false;

            foreach($fields as $field) {
                $fieldName = strlen($field->name) > 0 ? $field->name : $field->data;

                if(!$field->searchable) continue;

                if(array_key_exists($fieldName, $this->aliases)) {
                    $fieldName = $this->aliases[$fieldName];
                }

                if(!in_array($fieldName, $this->fields)) continue;

                if(!$firstLike) {
                    $this->builder->like($fieldName, $keyword->value);
                    $firstLike = true;
                } else {
                    $this->builder->orLike($fieldName, $keyword->value);
                }
            }

            $this->builder->groupEnd();
        }

        $this->isFilterApplied = true;
    }

    private function orderRecords()
    {
        $column = Request::order();

        if(!$column->orderable) return;

        if(array_key_exists($column->field, $this->aliases)) {
            $this->builder->orderBy($this->aliases[$column->field], $column->dir);
            return;
        }

        if(in_array($column->field, $this->fields)) {
            $this->builder->orderBy($column->field, $column->dir);
        }
    }

    private function limitRecords(): void
    {
        $req = Request::limit();
        $this->builder->limit($req->limit, $req->offset);
    }

    private function results()
    {
        $result = $this->builder->get();
        return (new DataProcessor($result, $this->columnDef))->result();
    }
}
