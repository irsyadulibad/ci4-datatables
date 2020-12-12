<?php namespace Irsyadulibad\DataTables;

class DataProcessor extends DataTableMethods
{
	public static function processResult($result)
	{
		$datatables = new self;
		$results = $result->getResultArray();
		$i = 0;

		foreach ($results as $data) {
			foreach($data as $key => $val) {
				// Check if raw columns exist
				if(in_array($key, self::$rawColumns)) 
					continue;

				$results[$i][$key] = esc($val);
			}

			$i++;
		}

		return $results;
	}
}
