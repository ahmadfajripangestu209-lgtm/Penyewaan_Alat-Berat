<?php
require __DIR__ . '/../vendor/autoload.php';
// Sample pembayaran data
$p = [
    'id' => 1234,
    'created_at' => date('Y-m-d H:i:s'),
    'nama_pemesan' => 'Budi Santoso',
    'email_pemesan' => 'budi@example.com',
    'nama_alat' => 'Excavator ZX200',
    'jumlah' => 4500000,
    'deposit' => 3000000,
];
$company = [];
try { $company = require __DIR__ . '/../app/Config/profil_perusahaan.php'; } catch (Throwable $e) {}
// Provide a simple esc() helper when running outside CodeIgniter
if (!function_exists('esc')) {
    function esc($str)
    {
        if (is_null($str)) return '';
        return htmlspecialchars((string)$str, ENT_QUOTES, 'UTF-8');
    }
}
ob_start();
extract(['pembayaran' => $p, 'company_name' => strtoupper($company['hero_title'] ?? 'Nama Perusahaan'), 'company_address' => $company['alamat'] ?? '', 'note' => $company['catatan_faktur'] ?? null]);
include __DIR__ . '/../app/Views/pembayaran/invoice.php';
$html = ob_get_clean();
$options = new \Dompdf\Options();
$options->setIsRemoteEnabled(true);
$dompdf = new \Dompdf\Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$out = $dompdf->output();
$file = __DIR__ . '/../writable/faktur_sample.pdf';
file_put_contents($file, $out);
echo "Wrote sample PDF to: $file\n";
