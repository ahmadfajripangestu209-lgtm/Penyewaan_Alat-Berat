<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateHargaFieldToVarchar extends Migration
{
    public function up()
    {
        // Ubah tipe data field harga menjadi VARCHAR
        $this->forge->modifyColumn('alat_berat', [
            'harga' => [
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
            'harga' => [
                'type' => 'INT',
                'null' => true,
            ],
        ]);
    }
}
