<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddHiddenByUserToRiwayatPenawaran extends Migration
{
    public function up()
    {
        $db = \Config\Database::connect();
        $fields = $db->getFieldNames('riwayat_penawaran');
        if (!in_array('hidden_by_user', $fields)) {
            $this->forge->addColumn('riwayat_penawaran', [
                'hidden_by_user' => [
                    'type' => 'TINYINT',
                    'constraint' => 1,
                    'default' => 0,
                    'after' => 'pesan_admin',
                ],
            ]);
        }
    }

    public function down()
    {
        $this->forge->dropColumn('riwayat_penawaran', 'hidden_by_user');
    }
}
