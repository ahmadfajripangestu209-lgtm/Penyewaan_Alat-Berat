<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class MigrateDataFromRiwayatKuotasiToRiwayatPenawaran extends Migration
{
    public function up()
    {
        // Check if both tables exist
        if ($this->db->tableExists('riwayat_kuotasi') && $this->db->tableExists('riwayat_penawaran')) {
            
            // Get all data from riwayat_kuotasi
            $query = $this->db->query("SELECT * FROM riwayat_kuotasi");
            $data = $query->getResultArray();
            
            if (!empty($data)) {
                // Insert data into riwayat_penawaran
                foreach ($data as $row) {
                    // Check if record already exists in riwayat_penawaran
                    $existing = $this->db->table('riwayat_penawaran')
                        ->where('id', $row['id'])
                        ->get()
                        ->getRowArray();
                    
                    if (!$existing) {
                        // Insert the record
                        $this->db->table('riwayat_penawaran')->insert($row);
                    }
                }
                
                log_message('info', 'Migrated ' . count($data) . ' records from riwayat_kuotasi to riwayat_penawaran');
            }
            
            // Now safely drop the old table
            $this->forge->dropTable('riwayat_kuotasi');
            log_message('info', 'Dropped riwayat_kuotasi table after successful migration');
        }
    }

    public function down()
    {
        // This migration cannot be safely reversed as it drops the original table
        // In a real production environment, you would want to implement a proper rollback
        log_message('warning', 'Cannot reverse migration: MigrateDataFromRiwayatKuotasiToRiwayatPenawaran');
    }
}
