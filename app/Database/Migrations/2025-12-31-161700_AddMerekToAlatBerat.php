<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddMerekToAlatBerat extends Migration
{
    public function up()
    {
        $db = \Config\Database::connect();
        $fields = $db->getFieldNames('alat_berat');
        if (!in_array('merek', $fields)) {
            $this->forge->addColumn('alat_berat', [
                'merek' => [
                    'type' => 'VARCHAR',
                    'constraint' => 100,
                    'null' => true,
                    'after' => 'nama', // Place it after 'nama' for logical ordering, or wherever appropriate
                ],
            ]);
        }
    }

    public function down()
    {
        $this->forge->dropColumn('alat_berat', 'merek');
    }
}
