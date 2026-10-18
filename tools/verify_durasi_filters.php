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
$base = "SELECT COUNT(*) FROM pembayaran p WHERE p.status = 'verified' AND (p.show_in_durasi IS NULL OR p.show_in_durasi = 1)";
$all = $pdo->query($base)->fetchColumn();
$aktif = $pdo->query($base . " AND p.rental_end_at > '" . date('Y-m-d H:i:s') . "'")->fetchColumn();
$selesai = $pdo->query($base . " AND p.rental_end_at IS NOT NULL AND p.rental_end_at <= '" . date('Y-m-d H:i:s') . "'")->fetchColumn();
$belum = $pdo->query($base . " AND p.rental_end_at IS NULL")->fetchColumn();

echo "Semua: {$all}\n";
echo "Aktif: {$aktif}\n";
echo "Selesai: {$selesai}\n";
echo "Belum Dimulai: {$belum}\n";
