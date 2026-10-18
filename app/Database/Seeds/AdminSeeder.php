<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run()
    {
        $data = [
            'name' => 'Administrator',
            'email' => 'admin@example.com',
            'password' => password_hash('admin', PASSWORD_DEFAULT),
            'role' => 'admin',
            'phone' => '081234567890',
            'address' => 'Kantor Pusat'
        ];

        // Insert using model to ensure validation or callbacks if any (though simple insert is safer here to bypass some)
        // Or simple query is fine.
        $this->db->table('users')->insert($data);
    }
}
