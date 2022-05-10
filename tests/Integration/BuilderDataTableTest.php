<?php

namespace Irsyadulibad\DataTables\Tests\Integration;

use CodeIgniter\Database\BaseBuilder;
use Irsyadulibad\DataTables\Tests\Support\Request\UserRequest;
use Irsyadulibad\DataTables\Tests\TestCase;

class BuilderDataTableTest extends TestCase
{
    protected BaseBuilder $builder;

    public function setUp(): void
    {
        parent::setUp();

        $_GET = (new UserRequest)->body();
        $this->builder = db_connect()->table('users');
    }

    public function tearDown(): void
    {
        unset($_GET);
    }

    /** @test */
    public function it_returns_all_records_when_default()
    {
        $dt = datatables($this->builder)->make();
        $json = json_decode($dt);

        $this->assertJson($dt);
        $this->assertEquals(1, $json->draw);
        $this->assertEquals(1000, $json->recordsTotal);
        $this->assertEquals(1000, $json->recordsFiltered);
    }

    /** @test */
    public function it_return_valid_columns()
    {
        $dt = datatables($this->builder)->make();
        $data = json_decode($dt, true)['data'][0];

        $this->assertArrayHasKey('username', $data);
        $this->assertArrayHasKey('email', $data);
        $this->assertArrayHasKey('password', $data);
        $this->assertArrayHasKey('name', $data);
    }

    /** @test */
    public function it_can_select_specify_columns()
    {
        $dt = datatables($this->builder->select('name'))->make();
        $json = json_decode($dt, true)['data'][0];

        $this->assertCount(1, $json);
    }

    /** @test */
    public function it_can_perform_global_search()
    {
        $_GET['search']['value'] = 'username-10';
        $dt = json_decode(datatables($this->builder)->make());

        $this->assertEquals(1000, $dt->recordsTotal);
        $this->assertEquals(12, $dt->recordsFiltered);
    }

    /** @test */
    public function it_can_sorting_columns()
    {
        $_GET['order'][0] = [
            'column' => '0',
            'dir' => 'asc'
        ];

        $dt = datatables($this->builder)->make();
        $this->assertEquals('name1', json_decode($dt)->data[0]->name);

        $_GET['order'][0] = [
            'column' => '1',
            'dir' => 'desc'
        ];

        $dt = datatables($this->builder)->make();
        $this->assertNotEquals('email-994', json_decode($dt)->data[5]->email);
    }

    /** @test */
    public function it_can_return_added_column()
    {
        $_GET['order'][0] = [
            'column' => '0',
            'dir' => 'asc'
        ];

        $dt = datatables($this->builder)->addColumn('emailWithName', function($data) {
                return "$data->email - $data->name";
            })->make();
        $json = json_decode($dt);

        $this->assertObjectHasAttribute('emailWithName', $json->data[0]);
        $this->assertEquals('email-1 - name1', $json->data[0]->emailWithName);
    }

    /** @test */
    public function it_can_return_edited_column()
    {
        $_GET['order'][0] = [
            'column' => '0',
            'dir' => 'asc'
        ];

        $dt = datatables($this->builder)->editColumn('name', function($value, $data) {
                return "$value-edited";
            })->make();
        $json = json_decode($dt);

        $this->assertEquals('name1-edited', $json->data[0]->name);
    }

    /** @test */
    public function it_can_hide_column()
    {
        $dt = datatables($this->builder)->hideColumns(['username'])->make();
        $this->assertObjectNotHasAttribute('username', json_decode($dt)->data[0]);
    }

    /** @test */
    public function it_can_add_indexed_column()
    {
        $dt = datatables($this->builder)->addIndexColumn()->make();
        $res = json_decode($dt);

        $this->assertObjectHasAttribute('DT_RowIndex', $res->data[0]);
        $this->assertEquals(2, $res->data[1]->DT_RowIndex);
        $this->assertEquals(10, $res->data[9]->DT_RowIndex);
    }

    /** @test */
    public function it_can_use_custom_index_column()
    {
        $dt = datatables('users')->addIndexColumn('RowIndex')->make();
        $data = json_decode($dt)->data[0];

        $this->assertObjectHasAttribute('RowIndex', $data);
        $this->assertEquals(1, $data->RowIndex);
    }

    /** @test */
    public function it_can_use_column_alias()
    {
        $dt = datatables($this->builder->select('name as user_name'))->make();
        $this->assertObjectHasAttribute('user_name', json_decode($dt)->data[0]);
    }

    /** @test */
    public function it_can_order_column_alias()
    {
        $_GET['order'][0] = [
            'column' => '0',
            'dir' => 'desc'
        ];

        $dt = datatables($this->builder->select('name as user_name'))->make();
        $this->assertEquals('name999', json_decode($dt)->data[0]->user_name);
    }

    /** @test */
    public function it_can_search_column_alias()
    {
        $_GET['search'] = [
            'value' => 'email-1000',
            'regex' => 'false'
        ];

        $_GET['columns'][2]['name'] = 'user_email';
        $_GET['columns'][2]['data'] = 'user_email';

        $dt = datatables('users')->select('email as user_email')->make();
        $this->assertEquals(1, json_decode($dt)->recordsFiltered);
    }

    /** @test */
    public function it_can_join_another_table()
    {
        $builder = $this->builder->select('users.*, addresses.name as address')
                    ->join('addresses', 'users.id = addresses.user_id');
        $data = json_decode(datatables($builder)->make());

        $this->assertEquals(1000, $data->recordsTotal);
        $this->assertEquals(1000, $data->recordsFiltered);
        $this->assertObjectHasAttribute('address', $data->data[0]);
    }

    /** @test */
    public function it_can_search_joined_table_fields()
    {
        $_GET['search'] = [
            'value' => 'al',
            'regex' => 'false'
        ];

        $builder = $this->builder->select('users.*, addresses.name as address')
                    ->join('addresses', 'users.id = addresses.user_id');
        $dt = datatables($builder)->make();
        
        $this->assertNotEquals(1000, json_decode($dt)->recordsFiltered);
    }
}
