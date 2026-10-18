<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddJamSewaToRiwayatPenawaran extends Migration
{
    public function up()
    {
        if ($this->db->tableExists('riwayat_penawaran') && ! $this->db->fieldExists('lama_sewa', 'riwayat_penawaran')) {
            $this->forge->addColumn('riwayat_penawaran', [
                'lama_sewa' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'null'       => true,
                    'default'    => null,
                ],
            ]);
        }
    }

    public function down()
    {
        if ($this->db->tableExists('riwayat_penawaran') && $this->db->fieldExists('lama_sewa', 'riwayat_penawaran')) {
            $this->forge->dropColumn('riwayat_penawaran', 'lama_sewa');
        }
    }
}
