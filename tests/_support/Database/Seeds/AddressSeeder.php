<?php

namespace Irsyadulibad\DataTables\Tests\Support\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AddressSeeder extends Seeder
{
    public function run()
    {
        $table = $this->db->table('addresses');

        if($table->countAllResults() < 1000) {
            $data = [];

            for($i = 1; $i <= 1000; $i++) {
                $data[] = [
                    'user_id' => $i,
                    'name' => $this->faker()->address
                ];
            }

            $table->insertBatch($data);
        }
    }
}
