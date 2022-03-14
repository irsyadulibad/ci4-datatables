<?php

namespace Irsyadulibad\DataTables;

use CodeIgniter\Database\ResultInterface;

class DataProcessor
{
	protected array $processColumn;

	protected ResultInterface $resultInterface;

	protected array $results;

	public function __construct(ResultInterface $result, array $columns)
	{
		$this->resultInterface = $result;
		$this->processColumn = $columns;
		$this->results = $result->getResultArray();
	}

	public function process(): array
	{
		if(!empty($this->processColumn['appends']))
			$this->addColumns();

		if(!empty($this->processColumn['edit']))
			$this->editColumns();

		if(!empty($this->processColumn['hidden']))
			$this->hide();

		$this->escapeColumns();

		return $this->results;
	}

	public function addColumns(): void
	{
		$result = $this->resultInterface->getResult();
		$appendCols = $this->processColumn['appends'];
		$i = 0;

		foreach($this->results as $data) {

			foreach($appendCols as $append) {
				$name = $append['name'];
				$callback = $append['callback'];

				$this->results[$i][$name] = $callback($result[$i]);
			}

			$i++;
		}
	}

	public function editColumns(): void
	{
		$editCols = $this->processColumn['edit'];
		$i = 0;

		foreach($this->results as $data) {

			foreach($editCols as $edit) {
				$name = $edit['name'];
				$callback = $edit['callback'];
				$result = $this->results[$i][$name];

				$this->results[$i][$name] = $callback($result);
			}

			$i++;
		}
	}

	public function hide(): void
	{

		$hideCols = $this->processColumn['hidden'];
		$i = 0;

		foreach ($this->results as $data) {
			foreach($data as $key => $val) {
				// Check if hide columns exist
				if(in_array($key, $hideCols)) {
					unset($this->results[$i][$key]);
				}

				continue;
			}

			$i++;
		}
	}

	public function escapeColumns(): void
	{
		$rawCols = $this->processColumn['raws'];
		$i = 0;

		foreach ($this->results as $data) {
			foreach($data as $key => $val) {
				// Check if raw columns exist
				if(in_array($key, $rawCols)) 
					continue;

				$this->results[$i][$key] = esc($val);
			}

			$i++;
		}
	}
}
