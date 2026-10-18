<?php
require __DIR__ . '/../vendor/autoload.php';
// Bootstrap CodeIgniter
$app = require_once __DIR__ . '/../app/Config/Bootstrap.php';
$db = \Config\Database::connect();
$builder = $db->table('pembayaran');
$rows = $builder->select('id, nama_alat, user_id, rental_end_at, created_at')->orderBy('id','DESC')->limit(10)->get()->getResultArray();
foreach ($rows as $r) {
    echo sprintf("id=%s | user_id=%s | nama_alat=%s | rental_end_at=%s | created_at=%s\n", $r['id'], $r['user_id'], $r['nama_alat'], $r['rental_end_at'], $r['created_at']);
}
