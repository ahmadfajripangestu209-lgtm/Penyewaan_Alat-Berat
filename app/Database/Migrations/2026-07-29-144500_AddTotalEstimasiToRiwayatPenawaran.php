<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddTotalEstimasiToRiwayatPenawaran extends Migration
{
    public function up()
    {
        $db = \Config\Database::connect();
        $fields = $db->getFieldNames('riwayat_penawaran');

        if (!in_array('total_estimasi', $fields)) {
            $this->forge->addColumn('riwayat_penawaran', [
                'total_estimasi' => [
                    'type' => 'INT',
                    'null' => true,
                    'default' => null,
                    'after' => 'harga_ditawarkan',
                ],
            ]);
        }
    }

    public function down()
    {
        $db = \Config\Database::connect();
        $fields = $db->getFieldNames('riwayat_penawaran');

        if (in_array('total_estimasi', $fields)) {
            $this->forge->dropColumn('riwayat_penawaran', 'total_estimasi');
        }
    }
}
