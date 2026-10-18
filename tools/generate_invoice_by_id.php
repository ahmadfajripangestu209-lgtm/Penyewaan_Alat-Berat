<?php
require __DIR__ . '/../vendor/autoload.php';
// Minimal test to generate PDF for pembayaran id provided as CLI arg
$id = $argv[1] ?? '13';
$id = intval($id);
if ($id <= 0) {
    echo "Invalid id\n"; exit(1);
}
// Load CI models (without full framework boot) - rely on model using DB config
require __DIR__ . '/../app/Config/Database.php';
// Create model instance
$model = new \App\Models\PembayaranModel();
$p = $model->find($id);
if (! $p) {
    echo "Pembayaran id=$id not found\n"; exit(1);
}
// company profile
$company = [];
try { $company = require __DIR__ . '/../app/Config/profil_perusahaan.php'; } catch (Throwable $e) {}

// render view
if (!function_exists('esc')) {
    function esc($s){ return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
}
ob_start();
extract(['pembayaran'=>$p, 'company_name'=>strtoupper($company['hero_title'] ?? 'Nama Perusahaan'), 'company_address'=>$company['alamat'] ?? '', 'note'=>$company['catatan_faktur'] ?? null]);
include __DIR__ . '/../app/Views/pembayaran/invoice.php';
$html = ob_get_clean();
$options = new \Dompdf\Options();
if (method_exists($options, 'setDefaultFont')) {
    $options->setDefaultFont('DejaVu Sans');
} else {
    try { $options->set('defaultFont','DejaVu Sans'); } catch (Throwable $_) {}
}
$options->setChroot(__DIR__ . '/../');
$options->setIsRemoteEnabled(true);
$dompdf = new \Dompdf\Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4','portrait');
$dompdf->render();
file_put_contents(__DIR__ . "/../writable/faktur_{$id}.pdf", $dompdf->output());
echo "Wrote writable/faktur_{$id}.pdf\n";
