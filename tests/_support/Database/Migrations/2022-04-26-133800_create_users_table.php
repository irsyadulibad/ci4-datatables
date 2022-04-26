<?php

namespace Irsyadulibad\DataTables\Test\Support\Database\Migrations;

use CodeIgniter\Database\Migration;

class createUsersTable extends Migration
{
    protected $DBGroup = 'tests';

    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'BIGINT',
                'constraint' => 20,
                'unsigned' => true,
                'auto_increment' => true
            ],
            'username' => [
                'type' => 'VARCHAR',
                'constraint' => 50
            ],
            'email' => [
                'type' => 'VARCHAR',
                'constraint' => 50
            ],
            'password' => [
                'type' => 'VARCHAR',
                'constraint' => 50
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => 50
            ]
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('users');
    }

    public function down()
    {
        $this->forge->dropTable('users');
    }
}
