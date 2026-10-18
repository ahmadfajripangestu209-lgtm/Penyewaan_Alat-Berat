<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddShowInDurasiToPembayaran extends Migration
{
    public function up()
    {
        $db = \Config\Database::connect();
        $fields = $db->getFieldNames('pembayaran');
        if (!in_array('show_in_durasi', $fields)) {
            $this->forge->addColumn('pembayaran', [
                'show_in_durasi' => [
                    'type' => 'TINYINT',
                    'constraint' => 1,
                    'null' => true,
                    'default' => 1,
                    'after' => 'rental_end_at',
                ],
            ]);
        }
    }

    public function down()
    {
        $db = \Config\Database::connect();
        $fields = $db->getFieldNames('pembayaran');
        if (in_array('show_in_durasi', $fields)) {
            $this->forge->dropColumn('pembayaran', 'show_in_durasi');
        }
    }
}
