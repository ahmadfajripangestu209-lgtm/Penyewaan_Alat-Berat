<?php
namespace App\Database\Migrations;
use CodeIgniter\Database\Migration;

class AddFotoToAlatBerat extends Migration
{
    public function up()
    {
        $this->forge->addColumn('alat_berat', [
            'foto' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'after' => 'status',
            ],
        ]);
    }
    public function down()
    {
        $this->forge->dropColumn('alat_berat', 'foto');
    }
} 