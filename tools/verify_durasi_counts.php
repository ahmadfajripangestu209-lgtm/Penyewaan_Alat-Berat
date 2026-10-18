<?php
$dbHost = '127.0.0.1';
$dbName = 'alat_berat';
$dbUser = 'root';
$dbPass = '';
try {
    $pdo = new PDO("mysql:host={$dbHost};dbname={$dbName};charset=utf8", $dbUser, $dbPass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
} catch (Exception $e) {
    echo "DB connect error: " . $e->getMessage() . PHP_EOL;
    exit(1);
}

// Old logic: rental_end_at is not null and status=verified
$old = $pdo->query("SELECT COUNT(*) FROM pembayaran WHERE status = 'verified' AND rental_end_at IS NOT NULL")->fetchColumn();
// New logic: status=verified and (show_in_durasi IS NULL OR show_in_durasi = 1)
$new = $pdo->query("SELECT COUNT(*) FROM pembayaran p WHERE p.status = 'verified' AND (p.show_in_durasi IS NULL OR p.show_in_durasi = 1)")->fetchColumn();
// Actual rows returned by durasiSewa query (join with riwayat_penawaran)
$rows = $pdo->query("SELECT COUNT(*) FROM pembayaran p LEFT JOIN riwayat_penawaran rp ON rp.id = p.riwayat_penawaran_id WHERE p.status = 'verified' AND (p.show_in_durasi IS NULL OR p.show_in_durasi = 1)")->fetchColumn();

echo "Old (rental_end_at not null): {$old}\n";
echo "New (show_in_durasi filter): {$new}\n";
echo "Rows returned by durasiSewa (join): {$rows}\n";
