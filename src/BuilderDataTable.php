<?php

namespace Irsyadulibad\DataTables;

use CodeIgniter\Database\BaseBuilder;
use Irsyadulibad\DataTables\Contracts\DataTableContract;
use Irsyadulibad\DataTables\Processors\DataProcessor;
use Irsyadulibad\DataTables\Utilities\Request;

class BuilderDataTable extends DataTableAbstract implements DataTableContract
{
    public function __construct(BaseBuilder $builder)
    {
        $this->builder = $builder;
    }

    public function make(bool $dump = false)
    {
        $this->totalRecords = $this->countTotal();
        $this->filterRecords();
        $this->countFiltered();
        $this->orderRecords();
        $this->limitRecords();

        return $this->render($dump, $this->results(), Request::draw());
    }

    private function filterRecords()
    {
        $fields = Request::fields();
        $keyword = Request::keyword();
        $firstLike = false;

        if(!empty($fields)) {
            $this->builder->groupStart();

            foreach($fields as $field) {
                $fieldName = strlen($field->name) > 0 ? $field->name : $field->data;

                if(!$field->searchable) continue;
    
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
        $this->builder->orderBy($column->field, $column->dir);
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
