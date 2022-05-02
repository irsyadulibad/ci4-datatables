<?php

namespace Irsyadulibad\DataTables\Tests\Support\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call(UserSeeder::class);
        $this->call(AddressSeeder::class);
    }
}
