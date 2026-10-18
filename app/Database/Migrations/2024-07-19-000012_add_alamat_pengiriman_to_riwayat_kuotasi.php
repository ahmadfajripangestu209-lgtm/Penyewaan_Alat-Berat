<?php
namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddAlamatPengirimanToRiwayatKuotasi extends Migration
{
    public function up()
    {
        $this->forge->addColumn('riwayat_kuotasi', [
            'alamat_pengiriman' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'telepon_pengunjung',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('riwayat_kuotasi', 'alamat_pengiriman');
    }
} 