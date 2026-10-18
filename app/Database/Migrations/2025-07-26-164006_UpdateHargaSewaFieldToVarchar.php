<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateHargaSewaFieldToVarchar extends Migration
{
    public function up()
    {
        // Ubah tipe data field harga_sewa menjadi VARCHAR
        $this->forge->modifyColumn('alat_berat', [
            'harga_sewa' => [
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
            'harga_sewa' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'default' => 0.00,
            ],
        ]);
    }
}
