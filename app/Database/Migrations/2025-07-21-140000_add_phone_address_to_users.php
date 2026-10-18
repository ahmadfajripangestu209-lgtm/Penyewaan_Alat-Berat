<?php
namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPhoneAddressToUsers extends Migration
{
    public function up()
    {
        $this->forge->addColumn('users', [
            'phone' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => true,
                'after' => 'role',
            ],
            'address' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'phone',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('users', ['phone', 'address']);
    }
} 