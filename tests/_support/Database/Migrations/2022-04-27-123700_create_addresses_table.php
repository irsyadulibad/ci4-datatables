<?php

namespace Irsyadulibad\DataTables\Test\Support\Database\Migrations;

use CodeIgniter\Database\Migration;

class createAddressesTable extends Migration
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
            'user_id' => [
                'type' => 'BIGINT',
                'constraint' => 20,
                'unsigned' => true,
            ],
            'name' => [
                'type' => 'TEXT'
            ]
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('user_id', 'users', 'id', 'cascade', 'cascade');
        $this->forge->createTable('addresses');
    }

    public function down()
    {
        $this->forge->dropTable('addresses');
    }
}
