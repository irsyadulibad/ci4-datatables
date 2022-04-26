<?php

namespace Irsyadulibad\DataTables\Tests\Support\Request;

class UserRequest
{
    public $draw = '1';

    public $columns = [
        [
            'name' => 'name',
            'data' => 'name',
            'searchable' => 'false',
            'orderable' => 'true',
            'search' => [
                'value' => '',
                'regex' => 'false'
            ]
        ],
        [
            'name' => 'username',
            'data' => 'username',
            'searchable' => 'true',
            'orderable' => 'false',
            'search' => [
                'value' => '',
                'regex' => 'false'
            ]
        ],
        [
            'name' => 'email',
            'data' => 'email',
            'searchable' => 'true',
            'orderable' => 'true',
            'search' => [
                'value' => '',
                'regex' => 'false'
            ]
        ]
    ];

    public $order = [
        [
            'column' => '0',
            'dir' => 'asc'
        ]
    ];

    public $length = 10;

    public $search = [
        'value' => '',
        'regex' => 'false'
    ];

    public function body()
    {
        return [
            'draw' => $this->draw,
            'columns' => $this->columns,
            'order' => $this->order,
            'length' => $this->length,
            'search' => $this->search
        ];
    }
}
