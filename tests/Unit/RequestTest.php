<?php

namespace Irsyadulibad\DataTables\Tests\Unit;

use CodeIgniter\Test\CIUnitTestCase;
use Irsyadulibad\DataTables\Tests\Support\Request\UserRequest;
use Irsyadulibad\DataTables\Utilities\Request;

class RequestTest extends CIUnitTestCase
{
    protected UserRequest $userReq;

    public function setUp(): void
    {
        parent::setUp();
        $this->userReq = new UserRequest;
    }

    public function tearDown(): void
    {
        parent::tearDown();
        unset($_GET);
    }

    public function test_is_searchable()
    {
        $_GET['columns'] = $this->userReq->columns;
        $fields = Request::fields();

        $this->assertIsArray($fields);
        $this->assertFalse($fields[0]->searchable);
        $this->assertTrue($fields[1]->searchable);
    }

    public function test_is_orderable()
    {
        $_GET['columns'] = $this->userReq->columns;
        $fields = Request::fields();

        $this->assertIsArray($fields);
        $this->assertTrue($fields[0]->orderable);
        $this->assertFalse($fields[1]->orderable);
    }

    public function test_column_keyword()
    {
        $columns = $this->userReq->columns;
        $columns[0]['search']['value'] = 'foo';
        $columns[1]['search']['value'] = 'bar';
        $_GET['columns'] = $columns;

        $fields = Request::fields();

        $this->assertEquals('foo', $fields[0]->search->value);
        $this->assertEquals('bar', $fields[1]->search->value);
    }

    public function test_orderable_columns()
    {
        $_GET['order'] = [];
        $order = Request::order();

        $this->assertEquals('', $order->field);
        $this->assertEquals('ASC', $order->dir);

        $_GET['order'] = $this->userReq->order;
        $order = Request::order();

        $this->assertEquals('', $order->field);
        $this->assertEquals('ASC', $order->dir);

        $_GET['columns'] = $this->userReq->columns;
        $order = Request::order();
        
        $this->assertEquals('name', $order->field);

        $_GET['order'][0]['dir'] = 'DESC';
        $order = Request::order();

        $this->assertEquals('DESC', $order->dir);
    }

    public function test_keyword()
    {
        $_GET['search'] = $this->userReq->search;
        $keyword = Request::keyword();

        $this->assertEquals('', $keyword->value);
        $this->assertFalse($keyword->regex);

        $_GET['search'] = [
            'value' => 'foo',
            'regex' => 'false'
        ];

        $this->assertEquals('foo', Request::keyword()->value);
    }
}
