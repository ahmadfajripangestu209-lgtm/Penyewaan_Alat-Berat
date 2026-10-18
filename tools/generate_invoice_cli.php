<?php
// Simple CLI helper to generate an invoice PDF for the first pembayaran row (for testing)
require __DIR__ . '/../vendor/autoload.php';
// Bootstrap minimal CodeIgniter environment for models/config
// We'll load database config manually
$dbConfig = require __DIR__ . '/../app/Config/Database.php';
// Extract default connection
$defaultGroup = $dbConfig['default'] ?? null;
$groupConfig = $dbConfig[$defaultGroup] ?? null;
if (!$groupConfig) {
    echo "Cannot find database config\n";
    exit(1);
}
$dsn = null;
try {
    $dsn = sprintf('mysql:host=%s;port=%s;dbname=%s;charset=%s', $groupConfig['hostname'], $groupConfig['port'] ?? 3306, $groupConfig['database'], $groupConfig['DBDriver'] === 'MySQLi' ? 'utf8mb4' : 'utf8');
    $pdo = new PDO('mysql:host='.$groupConfig['hostname'].';port=' . ($groupConfig['port'] ?? 3306) . ';dbname=' . $groupConfig['database'] . ';charset=utf8mb4', $groupConfig['username'], $groupConfig['password']);
} catch (Throwable $e) {
    echo "DB connection failed: " . $e->getMessage() . "\n";
    exit(1);
}
// Fetch one pembayaran
$stmt = $pdo->query('SELECT * FROM pembayaran ORDER BY id DESC LIMIT 1');
$p = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$p) {
    echo "No pembayaran rows found in database. Create one first.\n";
    exit(1);
}
// Load company profile
$company = [];
try { $company = require __DIR__ . '/../app/Config/profil_perusahaan.php'; } catch (Throwable $e) {}

// Render the view by including the view file and capturing output
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

// Generate PDF with Dompdf
$options = new \Dompdf\Options();
$options->setIsRemoteEnabled(true);
$dompdf = new \Dompdf\Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$out = $dompdf->output();
$file = __DIR__ . '/../writable/faktur_test.pdf';
file_put_contents($file, $out);
echo "Wrote PDF to: $file\n";
