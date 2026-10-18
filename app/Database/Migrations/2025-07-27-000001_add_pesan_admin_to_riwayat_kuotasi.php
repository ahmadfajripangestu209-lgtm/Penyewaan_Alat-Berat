<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPesanAdminToRiwayatKuotasi extends Migration
{
    public function up()
    {
        $this->forge->addColumn('riwayat_kuotasi', [
            'pesan_admin' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'catatan',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('riwayat_kuotasi', 'pesan_admin');
    }
} 