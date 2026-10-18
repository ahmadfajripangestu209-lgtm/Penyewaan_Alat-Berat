<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class RenameRiwayatKuotasiToRiwayatPenawaran extends Migration
{
    public function up()
    {
        // Check if old table exists and new table doesn't exist
        if ($this->db->tableExists('riwayat_kuotasi') && !$this->db->tableExists('riwayat_penawaran')) {
            // Rename table from riwayat_kuotasi to riwayat_penawaran
            $this->forge->renameTable('riwayat_kuotasi', 'riwayat_penawaran');
        }
    }

    public function down()
    {
        // Check if new table exists and old table doesn't exist
        if ($this->db->tableExists('riwayat_penawaran') && !$this->db->tableExists('riwayat_kuotasi')) {
            // Revert back to original table name
            $this->forge->renameTable('riwayat_penawaran', 'riwayat_kuotasi');
        }
    }
}
