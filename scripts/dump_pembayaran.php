<?php
// Dump recent pembayaran rows (id, user_id, rental_end_at)
chdir(__DIR__ . '/..');
require 'vendor/autoload.php';
// Load Config classes
// Note: we rely on Config\Database available after autoload
$db = \Config\Database::connect();
$builder = $db->table('pembayaran');
$rows = $builder->select('id, user_id, rental_end_at, created_at')->orderBy('created_at', 'DESC')->limit(20)->get()->getResultArray();
foreach ($rows as $r) {
    echo "id={$r['id']} user_id={$r['user_id']} rental_end_at={$r['rental_end_at']} created_at={$r['created_at']}\n";
}
