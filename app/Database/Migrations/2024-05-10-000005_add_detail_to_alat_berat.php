<?php
namespace App\Database\Migrations;
use CodeIgniter\Database\Migration;

class AddDetailToAlatBerat extends Migration
{
    public function up()
    {
        $this->forge->addColumn('alat_berat', [
            'tipe' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
                'after' => 'nama',
            ],
            'harga' => [
                'type' => 'INT',
                'null' => true,
                'after' => 'kapasitas',
            ],
            'minimum_sewa' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => true,
                'after' => 'harga',
            ],
            'operator' => [
                'type' => 'INT',
                'null' => true,
                'after' => 'minimum_sewa',
            ],
        ]);
    }
    public function down()
    {
        $this->forge->dropColumn('alat_berat', ['tipe', 'harga', 'minimum_sewa', 'operator']);
    }
} 