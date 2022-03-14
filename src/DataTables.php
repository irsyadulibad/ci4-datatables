<?php

namespace Irsyadulibad\DataTables;

use CodeIgniter\Database\Config;

class DataTables
{

	public static function use($table)
	{
		return self::create($table);
	}

	public static function create($table)
	{
		$db = Config::connect();
		return new TableProcessor($db, $table);
	}
}
