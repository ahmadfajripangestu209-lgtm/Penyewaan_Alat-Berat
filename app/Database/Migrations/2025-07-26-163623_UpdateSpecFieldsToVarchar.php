<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateSpecFieldsToVarchar extends Migration
{
    public function up()
    {
        // Ubah tipe data field spesifikasi menjadi VARCHAR
        $this->forge->modifyColumn('alat_berat', [
            'berat_operasional' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
            ],
            'kapasitas_bucket' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
            ],
            'kedalaman_gali_maksimum' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
            ],
            'tinggi_transportasi' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
            ],
            'panjang_transportasi' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
            ],
            'lebar_transportasi' => [
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
            'berat_operasional' => [
                'type' => 'INT',
                'null' => true,
            ],
            'kapasitas_bucket' => [
                'type' => 'DECIMAL',
                'constraint' => '6,3',
                'null' => true,
            ],
            'kedalaman_gali_maksimum' => [
                'type' => 'DECIMAL',
                'constraint' => '6,3',
                'null' => true,
            ],
            'tinggi_transportasi' => [
                'type' => 'DECIMAL',
                'constraint' => '5,2',
                'null' => true,
            ],
            'panjang_transportasi' => [
                'type' => 'DECIMAL',
                'constraint' => '5,2',
                'null' => true,
            ],
            'lebar_transportasi' => [
                'type' => 'DECIMAL',
                'constraint' => '5,2',
                'null' => true,
            ],
        ]);
    }
}
