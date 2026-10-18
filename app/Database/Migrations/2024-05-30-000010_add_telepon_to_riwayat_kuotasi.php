<?php
namespace App\Database\Migrations;
use CodeIgniter\Database\Migration;

class AddTeleponToRiwayatKuotasi extends Migration
{
    public function up()
    {
        $this->forge->addColumn('riwayat_kuotasi', [
            'telepon_pengunjung' => [
                'type' => 'VARCHAR',
                'constraint' => 30,
                'null' => true,
                'after' => 'email_pengunjung',
            ],
        ]);
    }
    public function down()
    {
        $this->forge->dropColumn('riwayat_kuotasi', 'telepon_pengunjung');
    }
} 