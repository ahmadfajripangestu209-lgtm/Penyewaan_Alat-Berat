<?php
// Quick test script to simulate hiding a riwayat_penawaran row and checking counts.
// Configure DB connection to match app/Config/Database.php or environment
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
// Create a dummy riwayat_penawaran if none exists
$exists = $pdo->query("SELECT COUNT(*) FROM riwayat_penawaran")->fetchColumn();
if ($exists == 0) {
    $pdo->exec("INSERT INTO riwayat_penawaran (tanggal_minta, status, alat_berat_id, created_at, updated_at) VALUES (NOW(), 'pending', 1, NOW(), NOW())");
}
// Insert a test row owned by user_id = 9999 to avoid conflicts
$pdo->exec("INSERT INTO riwayat_penawaran (user_id, tanggal_minta, status, alat_berat_id, created_at, updated_at, hidden_by_user) VALUES (9999, NOW(), 'pending', 1, NOW(), NOW(), 0)");
$insertId = $pdo->lastInsertId();
echo "Inserted test riwayat_penawaran id={$insertId}\n";
$totalAll = $pdo->query("SELECT COUNT(*) FROM riwayat_penawaran")->fetchColumn();
$visibleForUser = $pdo->query("SELECT COUNT(*) FROM riwayat_penawaran WHERE user_id = 9999 AND hidden_by_user = 0")->fetchColumn();
echo "Total all riwayat_penawaran: {$totalAll}\n";
echo "Visible for user 9999 before hide: {$visibleForUser}\n";
// Mark hidden
$pdo->exec("UPDATE riwayat_penawaran SET hidden_by_user = 1 WHERE id = " . intval($insertId));
$visibleForUserAfter = $pdo->query("SELECT COUNT(*) FROM riwayat_penawaran WHERE user_id = 9999 AND hidden_by_user = 0")->fetchColumn();
$totalAllAfter = $pdo->query("SELECT COUNT(*) FROM riwayat_penawaran")->fetchColumn();
echo "Visible for user 9999 after hide: {$visibleForUserAfter}\n";
echo "Total all riwayat_penawaran after hide: {$totalAllAfter}\n";
// cleanup: remove our test row
$pdo->exec("DELETE FROM riwayat_penawaran WHERE id = " . intval($insertId));
echo "Cleanup done.\n";
