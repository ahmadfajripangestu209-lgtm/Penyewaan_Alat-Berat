<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$db = 'alat_berat';
$mysqli = new mysqli($host, $user, $pass, $db);
if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error . "\n";
    exit(1);
}
$res = $mysqli->query("SELECT id, user_id, nama_alat, rental_end_at, created_at FROM pembayaran ORDER BY id DESC LIMIT 20");
while ($row = $res->fetch_assoc()) {
    echo sprintf("id=%s | user_id=%s | nama_alat=%s | rental_end_at=%s | created_at=%s\n", $row['id'], $row['user_id'], $row['nama_alat'], $row['rental_end_at'], $row['created_at']);
}
$res->free();
$mysqli->close();
