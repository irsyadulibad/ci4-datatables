<?php

namespace Irsyadulibad\DataTables\Utilities;

use CodeIgniter\Config\Services;

class Request
{
	public static function fields(): array
	{
		$columns = static::get('columns') ?? [];

		return array_map(function($column) {
			return (object)[
				'data' => esc($column['data']),
				'name' => esc($column['name']),
				'searchable' => boolval($column['searchable']),
				'orderable' => boolval($column['orderable']),
				'search' => (object)[
					'value' => esc($column['search']['value']),
					'regex' => boolval($column['search']['regex'])
				]
			];
		}, $columns);
	}

	public static function keyword(): object
	{
		$keyword = self::get('search');

		return (object)[
			'value'	=> esc($keyword['value'] ?? ''),
			'regex' => boolval($keyword['regex'] ?? false)
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
		$column = $order['column'] ?? null;

		if(!is_null($order) && !is_null($column)) {
			return (object)[
				'field' => static::get('columns')[$column]['data'],
				'dir' => $order['dir'] ?? 'ASC'
			];
		}

		return (object)[
			'field' => '',
			'dir' => 'ASC'
		];
	}

	private static function get($name = '')
	{
		return Services::request()->getGetPost($name);
	}
}
