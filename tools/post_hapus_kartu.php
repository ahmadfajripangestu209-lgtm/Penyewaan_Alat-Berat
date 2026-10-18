<?php
$url = 'http://localhost:8080/index.php/admin/pembayaran/hapus-kartu-durasi/6';
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
// Send CSRF token if needed - currently not sending
$res = curl_exec($ch);
$info = curl_getinfo($ch);
echo 'HTTP_CODE: ' . $info['http_code'] . "\n";
echo substr($res,0,1000);
curl_close($ch);
