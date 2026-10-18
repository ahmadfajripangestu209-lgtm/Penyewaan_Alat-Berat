<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddRentalEndAtToPembayaran extends Migration
{
    public function up()
    {
        $db = \Config\Database::connect();
        $fields = $db->getFieldNames('pembayaran');
        if (!in_array('rental_end_at', $fields)) {
            $this->forge->addColumn('pembayaran', [
                'rental_end_at' => [
                    'type' => 'DATETIME',
                    'null' => true,
                    'after' => 'status',
                ],
            ]);
        }
    }

    public function down()
    {
        $db = \Config\Database::connect();
        $fields = $db->getFieldNames('pembayaran');
        if (in_array('rental_end_at', $fields)) {
            $this->forge->dropColumn('pembayaran', 'rental_end_at');
        }
    }
}
