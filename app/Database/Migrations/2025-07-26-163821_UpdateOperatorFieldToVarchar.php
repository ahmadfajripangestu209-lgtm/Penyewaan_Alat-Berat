<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateOperatorFieldToVarchar extends Migration
{
    public function up()
    {
        // Ubah tipe data field operator menjadi VARCHAR
        $this->forge->modifyColumn('alat_berat', [
            'operator' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
            ],
        ]);
    }

    public function down()
    {
        // Kembalikan ke tipe data semula jika perlu
        $this->forge->modifyColumn('alat_berat', [
            'operator' => [
                'type' => 'INT',
                'null' => true,
            ],
        ]);
    }
}
