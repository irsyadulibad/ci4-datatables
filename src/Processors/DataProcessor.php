<?php

namespace Irsyadulibad\DataTables\Processors;

use CodeIgniter\Database\ResultInterface;

class DataProcessor
{
    protected array $columnDef;

    private array $results;

    public function __construct(ResultInterface $interface, array $columnDef)
    {
        $this->columnDef = $columnDef;
        $this->results = $interface->getResultArray();
    }

    public function result(): array
    {
        if(!empty($this->columnDef['append'])) $this->appendFields();

        if(!empty($this->columnDef['edit'])) $this->editFields();

        if(!empty($this->columnDef['hide'])) $this->hideFields();

        if($this->columnDef['index']['indexed']) $this->addIndexField();

        $this->escapeFields();

        return $this->results;
    }

    private function appendFields(): void
    {
        for($i = 0; $i < count($this->results); $i++) {
            foreach($this->columnDef['append'] as $append) {
                $name = $append['name'];
                $callback = $append['callback'];
                $data = (object)$this->results[$i];

                $this->results[$i][$name] = $callback($data);
            }
        }
    }

    private function editFields(): void
    {
        for($i = 0; $i < count($this->results); $i++) {
            foreach($this->columnDef['edit'] as $edit) {
                $name = $edit['name'];
                $callback = $edit['callback'];
                $data = (object)$this->results[$i];

                $this->results[$i][$name] = $callback($data->{$name}, $data);
            }
        }
    }

    private function hideFields(): void
    {
        for($i = 0; $i < count($this->results); $i++) {
            foreach($this->results[$i] as $key => $val) {

                if(in_array($key, $this->columnDef['hide'])) {
                    unset($this->results[$i][$key]);
                }
            }
        }
    }

    private function escapeFields(): void
    {
        for($i = 0; $i < count($this->results); $i++) {
            foreach($this->results[$i] as $key => $val) {
                if(in_array($key, $this->columnDef['raw'])) continue;

                $this->results[$i][$key] = esc($val);
            }
        }
    }

    private function addIndexField(): void
    {
        $rowid = $this->columnDef['index']['rowID'];

        for($i = 0; $i < count($this->results); $i++) {
            $this->results[$i][$rowid] = $i + 1;
        }
    }
}
