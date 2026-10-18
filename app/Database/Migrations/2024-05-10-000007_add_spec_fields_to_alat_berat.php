<?php
namespace App\Database\Migrations;
use CodeIgniter\Database\Migration;

class AddSpecFieldsToAlatBerat extends Migration
{
    public function up()
    {
        $this->forge->addColumn('alat_berat', [
            'berat_operasional' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
                'after' => 'operator',
            ],
            'kapasitas_bucket' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
                'after' => 'berat_operasional',
            ],
            'kedalaman_gali_maksimum' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
                'after' => 'kapasitas_bucket',
            ],
            'tinggi_transportasi' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
                'after' => 'kedalaman_gali_maksimum',
            ],
            'panjang_transportasi' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
                'after' => 'tinggi_transportasi',
            ],
            'lebar_transportasi' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
                'after' => 'panjang_transportasi',
            ],
        ]);
    }
    public function down()
    {
        $this->forge->dropColumn('alat_berat', [
            'berat_operasional',
            'kapasitas_bucket',
            'kedalaman_gali_maksimum',
            'tinggi_transportasi',
            'panjang_transportasi',
            'lebar_transportasi',
        ]);
    }
} 