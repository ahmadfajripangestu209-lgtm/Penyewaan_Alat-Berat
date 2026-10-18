<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAlatBeratGambar extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'alat_berat_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'filename' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('alat_berat_id', 'alat_berat', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('alat_berat_gambar');
    }

    public function down()
    {
        $this->forge->dropTable('alat_berat_gambar');
    }
} 