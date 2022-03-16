<?php

namespace Irsyadulibad\DataTables;

use CodeIgniter\Database\BaseConnection;
use Irsyadulibad\DataTables\Contracts\DataTableContract;
use Irsyadulibad\DataTables\Processors\DataProcessor;
use Irsyadulibad\DataTables\Utilities\Request;

class QueryDataTable extends DataTableAbstract implements DataTableContract
{
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
        $firstLike = false;

        if(!empty($fields)) {
            $this->builder->groupStart();

            foreach($fields as $field) {
                if(!$field->searchable) continue;
                if(!in_array($field->name, $this->fields)) continue;
    
                if(array_key_exists($field->name, $this->aliases)) {
                    $field = $this->aliases[$field];
                }
    
                if(!$firstLike) {
                    $this->builder->like($field->name, $keyword->value);
                    $firstLike = true;
                } else {
                    $this->builder->orLike($field->name, $keyword->value);
                }
            }
    
            if(!empty($fields)) $this->builder->groupEnd();
        }

        $this->isFilterApplied = true;
    }

    private function orderRecords()
    {
        $order = Request::order();

        if(array_key_exists($order->field, $this->aliases)) {
            $this->builder->orderBy($this->aliases[$order->field], $order->dir);
            return;
        }

        if(in_array($order->field, $this->fields)) {
            $this->builder->orderBy($order->field, $order->dir);
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
