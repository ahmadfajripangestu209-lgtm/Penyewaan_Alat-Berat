<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddTeleponPengunjungToRiwayatPenawaran extends Migration
{
    public function up()
    {
        // Check if table exists and field doesn't exist
        if ($this->db->tableExists('riwayat_penawaran') && !$this->db->fieldExists('telepon_pengunjung', 'riwayat_penawaran')) {
            $this->forge->addColumn('riwayat_penawaran', [
                'telepon_pengunjung' => [
                    'type' => 'VARCHAR',
                    'constraint' => 30,
                    'null' => true,
                    'after' => 'email_pengunjung',
                ],
            ]);
        }
    }

    public function down()
    {
        // Check if table exists and field exists
        if ($this->db->tableExists('riwayat_penawaran') && $this->db->fieldExists('telepon_pengunjung', 'riwayat_penawaran')) {
            $this->forge->dropColumn('riwayat_penawaran', 'telepon_pengunjung');
        }
    }
}
