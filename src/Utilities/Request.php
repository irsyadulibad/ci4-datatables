<?php

namespace Irsyadulibad\DataTables\Utilities;

class Request
{
	public static function fields(): array
	{
		$columns = static::get('columns') ?? [];

		return array_map(function($column) {
			return (object)[
				'data' => esc($column['data']),
				'name' => esc($column['name']),
				'searchable' => static::toBool($column['searchable']),
				'orderable' => static::toBool($column['orderable']),
				'search' => (object)[
					'value' => esc($column['search']['value']),
					'regex' => static::toBool($column['search']['regex'])
				]
			];
		}, $columns);
	}

	public static function keyword(): object
	{
		$keyword = self::get('search');

		return (object)[
			'value'	=> esc($keyword['value'] ?? ''),
			'regex' => static::toBool($keyword['regex'] ?? false)
		];
	}

	public static function limit(): object
	{
		return (object)[
			'limit' => intval(static::get('length') ?? 10),
			'offset' => intval(static::get('start'))
		];
	}

	public static function draw()
	{
		return intval(static::get('draw'));
	}

	public static function order(): object
	{
		$order = static::get('order')[0] ?? null;
		$columns = static::fields();
		$column = $order['column'] ?? null;

		if(!is_null($order) && !is_null($column) && isset($columns[$column])) {
			return (object)[
				'field' => $columns[$column]->data,
				'dir' => $order['dir'] ?? 'ASC',
				'orderable' => $columns[$column]->orderable
			];
		}

		return (object)[
			'field' => '',
			'dir' => 'ASC',
			'orderable' => false
		];
	}

	protected static function toBool($value)
	{
		return filter_var($value, FILTER_VALIDATE_BOOLEAN);
	}

	protected static function get($name = '')
	{
		return isset($_GET[$name]) ? $_GET[$name] : $_POST[$name] ?? null;
	}
}
