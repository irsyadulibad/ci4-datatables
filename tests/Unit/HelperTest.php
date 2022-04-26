<?php

namespace Irsyadulibad\DataTables\Tests\Unit;

use CodeIgniter\Test\CIUnitTestCase;
use Irsyadulibad\DataTables\QueryDataTable;

class HelperTest extends CIUnitTestCase
{
    public function test_is_helper_return_valid_class()
    {
        $this->assertInstanceOf(QueryDataTable::class, datatables('users'));
    }
}
