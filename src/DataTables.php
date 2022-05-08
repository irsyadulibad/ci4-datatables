<?php

namespace Irsyadulibad\DataTables;

use CodeIgniter\Database\BaseBuilder;
use CodeIgniter\Database\Config;

class DataTables
{

	public static function use($source)
	{
		return self::create($source);
	}

	public static function create($source)
	{
		if(gettype($source) == "string") {
			$db = Config::connect();
			return new QueryDataTable($db, $source);
		}

		if($source instanceof BaseBuilder) {
			return new BuilderDataTable($source);
		}
	}
}
