<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddRequiredFieldsToAlatBerat extends Migration
{
    public function up()
    {
        $fields = [
            'deskripsi' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'nama'
            ],
            'gambar' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'after' => 'deskripsi'
            ],
            'harga_sewa' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'default' => 0.00,
                'after' => 'status'
            ]
        ];

        $this->forge->addColumn('alat_berat', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('alat_berat', ['deskripsi', 'gambar', 'harga_sewa']);
    }
} 