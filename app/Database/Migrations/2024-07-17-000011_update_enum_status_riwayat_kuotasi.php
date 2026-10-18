<?php
namespace App\Database\Migrations;
use CodeIgniter\Database\Migration;

class UpdateEnumStatusRiwayatKuotasi extends Migration
{
    public function up()
    {
        $this->db->query("ALTER TABLE riwayat_kuotasi MODIFY status ENUM('pending','diterima','ditolak') DEFAULT 'pending'");
    }
    public function down()
    {
        $this->db->query("ALTER TABLE riwayat_kuotasi MODIFY status ENUM('pending','ditawarkan','ditolak') DEFAULT 'pending'");
    }
} 