<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddJumlahAjukanUlangToRiwayatKuotasi extends Migration
{
    public function up()
    {
        $this->forge->addColumn('riwayat_kuotasi', [
            'jumlah_ajukan_ulang' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0,
                'after' => 'pesan_admin',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('riwayat_kuotasi', 'jumlah_ajukan_ulang');
    }
} 