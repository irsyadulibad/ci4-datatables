<?php namespace Irsyadulibad\DataTables;

use Irsyadulibad\DataTables\Processor;
use \Config\Database;

class DataTables
{

	public static function use($table)
	{
		return self::create($table);
	}

	public static function create($table)
	{
		$db = Database::connect();
		return new TableProcessor($db, $table);
	}
}
