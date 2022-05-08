<?php

namespace Irsyadulibad\DataTables\Tests\Integration;

use Irsyadulibad\DataTables\BuilderDataTable;
use Irsyadulibad\DataTables\DataTables;
use Irsyadulibad\DataTables\QueryDataTable;
use Irsyadulibad\DataTables\Tests\TestCase;

class DataTablesTest extends TestCase
{
    public function test_is_valid_return_class()
    {
        $datatables = DataTables::use('users');
        $this->assertInstanceOf(QueryDataTable::class, $datatables);

        $datatables = DataTables::use(db_connect()->table('users'));
        $this->assertInstanceOf(BuilderDataTable::class, $datatables);
    }
}
