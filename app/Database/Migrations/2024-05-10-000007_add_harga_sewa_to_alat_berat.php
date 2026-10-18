<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddHargaSewaToAlatBerat extends Migration
{
    public function up()
    {
        // Kolom harga_sewa sudah ada, jadi tidak perlu menambah lagi.
        // $this->forge->addColumn('alat_berat', [
        //     'harga_sewa' => [
        //         'type' => 'DECIMAL',
        //         'constraint' => '10,2',
        //         'default' => 0,
        //         'after' => 'status',
        //     ],
        // ]);
    }

    public function down()
    {
        $this->forge->dropColumn('alat_berat', 'harga_sewa');
    }
} 