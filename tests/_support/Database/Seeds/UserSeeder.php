<?php

namespace Irsyadulibad\DataTables\Tests\Support\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $data = [];

        for($i = 1; $i <= 1000; $i++) {
            $data[] = [
                'username' => $this->faker()->userName(),
                'email' => $this->faker()->email(),
                'password' => $this->faker()->password(),
                'name' => $this->faker()->name(),
            ];
        }

        $this->db->table('users')->insertBatch($data);
    }
}
