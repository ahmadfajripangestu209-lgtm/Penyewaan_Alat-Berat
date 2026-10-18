<?php
$mysqli = new mysqli('localhost','root','','alat_berat');
if ($mysqli->connect_errno) { echo "error connect"; exit; }
$res = $mysqli->query("SELECT id, nama_alat, show_in_durasi, rental_end_at FROM pembayaran WHERE status='verified' ORDER BY id DESC LIMIT 20");
while ($r = $res->fetch_assoc()) {
    echo $r['id']." | show_in_durasi=".($r['show_in_durasi']===null? 'NULL': $r['show_in_durasi'])." | rental_end_at=".($r['rental_end_at'] ?? 'NULL')." | nama=".$r['nama_alat']."\n";
}
$res->free();
$mysqli->close();
