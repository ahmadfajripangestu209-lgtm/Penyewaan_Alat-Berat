<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddMinHargaPenawaranToAlatBerat extends Migration
{
    public function up()
    {
        $this->forge->addColumn('alat_berat', [
            'min_harga_penawaran' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
                'after' => 'harga_sewa',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('alat_berat', 'min_harga_penawaran');
    }
}
