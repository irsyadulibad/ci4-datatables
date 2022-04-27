<?php

namespace Irsyadulibad\DataTables\Tests\Support\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        if($this->db->table('users')->countAllResults() < 1000) {
            $data = [];
            
            for($i = 1; $i <= 1000; $i++) {
                $data[] = [
                    'username' => 'username-' . $i,
                    'email' => 'email-' . $i,
                    'password' => 'password-' . $i,
                    'name' => 'name' . $i,
                ];
            }
    
            $this->db->table('users')->insertBatch($data);   
        }
    }
}
