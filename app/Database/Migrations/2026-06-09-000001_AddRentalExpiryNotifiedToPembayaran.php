<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddRentalExpiryNotifiedToPembayaran extends Migration
{
    public function up()
    {
        $db = \Config\Database::connect();
        $fields = $db->getFieldNames('pembayaran');
        if (!in_array('rental_expiry_notified', $fields)) {
            $this->forge->addColumn('pembayaran', [
                'rental_expiry_notified' => [
                    'type' => 'TINYINT',
                    'constraint' => 1,
                    'null' => true,
                    'default' => 0,
                    'after' => 'show_in_durasi',
                ],
            ]);
        }
    }

    public function down()
    {
        $db = \Config\Database::connect();
        $fields = $db->getFieldNames('pembayaran');
        if (in_array('rental_expiry_notified', $fields)) {
            $this->forge->dropColumn('pembayaran', 'rental_expiry_notified');
        }
    }
}
