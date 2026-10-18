<?php
require __DIR__ . '/../vendor/autoload.php';
$db = \Config\Database::connect();
$rows = $db->table('pembayaran')
    ->select('id, status, rental_end_at, updated_at, created_at, riwayat_penawaran_id')
    ->orderBy('created_at', 'DESC')
    ->limit(20)
    ->get()
    ->getResultArray();
foreach ($rows as $row) {
    echo sprintf(
        "id=%s status=%s rental_end_at=%s created_at=%s updated_at=%s riwayat_penawaran_id=%s\n",
        $row['id'], $row['status'], $row['rental_end_at'] ?? 'NULL', $row['created_at'] ?? 'NULL', $row['updated_at'] ?? 'NULL', $row['riwayat_penawaran_id'] ?? 'NULL'
    );
}
