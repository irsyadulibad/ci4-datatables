<?php namespace Irsyadulibad\DataTables;

class DataProcessor extends DataTableMethods
{
	public static function processResult($result)
	{
		$results = $result->getResultArray();
		$i = 0;

		foreach ($results as $data) {
			foreach($data as $key => $val) {
				$results[$i][$key] = esc($val);
			}

			$i++;
		}

		return $results;
	}
}
