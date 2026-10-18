<?php
if (!function_exists('invoice_data_uri')) {
    function invoice_data_uri($relativePath)
    {
        $path = rtrim(FCPATH, '\\/') . DIRECTORY_SEPARATOR . str_replace(['/', '\\'], DIRECTORY_SEPARATOR, ltrim((string) $relativePath, '\\/'));
        if (!is_file($path)) {
            return null;
        }

        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        $mime = 'image/jpeg';
        if ($ext === 'png') {
            $mime = 'image/png';
        } elseif ($ext === 'gif') {
            $mime = 'image/gif';
        } elseif ($ext === 'webp') {
            $mime = 'image/webp';
        }

        $content = @file_get_contents($path);
        if ($content === false) {
            return null;
        }

        return 'data:' . $mime . ';base64,' . base64_encode($content);
    }
}

if (!function_exists('invoice_svg_data_uri')) {
    function invoice_svg_data_uri($svg)
    {
        return 'data:image/svg+xml;base64,' . base64_encode($svg);
    }
}

if (!function_exists('invoice_info_icon')) {
    function invoice_info_icon($type)
    {
        $svgMap = [
            'document' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M7 3h7l5 5v11a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2Zm6 1.5V9h4.5" fill="none" stroke="#fff" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/><path d="M9 12h6M9 15h6" fill="none" stroke="#fff" stroke-width="2.2" stroke-linecap="round"/></svg>',
            'calendar' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><rect x="4" y="6.5" width="16" height="13" rx="2" fill="none" stroke="#fff" stroke-width="2.2"/><path d="M8 4.5v4M16 4.5v4M4 10h16" fill="none" stroke="#fff" stroke-width="2.2" stroke-linecap="round"/></svg>',
            'user' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><circle cx="12" cy="8" r="3.2" fill="none" stroke="#fff" stroke-width="2.2"/><path d="M6.5 18c1.3-2.6 3.3-4 5.5-4s4.2 1.4 5.5 4" fill="none" stroke="#fff" stroke-width="2.2" stroke-linecap="round"/></svg>',
            'truck' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M3.5 8.5h10v6h-10zM13.5 10h3l2 2v2h-5z" fill="none" stroke="#fff" stroke-width="2.2" stroke-linejoin="round"/><circle cx="8" cy="17" r="1.8" fill="none" stroke="#fff" stroke-width="2.2"/><circle cx="17" cy="17" r="1.8" fill="none" stroke="#fff" stroke-width="2.2"/></svg>',
            'clock' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><circle cx="12" cy="12" r="8" fill="none" stroke="#fff" stroke-width="2.2"/><path d="M12 7.5v5l3 2" fill="none" stroke="#fff" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
            'bank' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M4.5 9h15M6.5 9V17M10.5 9V17M14.5 9V17M18 9V17M4 18.5h16M12 4l8 3H4l8-3Z" fill="none" stroke="#fff" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
            'shield-check' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M12 3l7 3v5c0 4.4-2.7 8.4-7 10-4.3-1.6-7-5.6-7-10V6l7-3Z" fill="#f4b400"/><path d="M8.6 12.4l2.1 2.1 4.7-4.9" fill="none" stroke="#07152d" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
            'phone' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M7.2 4.5c.4-.4 1-.6 1.5-.4l2.1.9c.6.2.9.8.8 1.4l-.4 2.2c-.1.4 0 .8.3 1.1l2.4 2.4c.3.3.7.4 1.1.3l2.2-.4c.6-.1 1.2.2 1.4.8l.9 2.1c.2.5 0 1.1-.4 1.5l-1 1c-.9.9-2.2 1.3-3.5 1-2-.4-4.3-1.7-6.4-3.8-2.1-2.1-3.4-4.4-3.8-6.4-.3-1.3.1-2.6 1-3.5l1-1Z" fill="none" stroke="#f4b400" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
            'location' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M12 20c4-4.6 6-8 6-10.5A6 6 0 0 0 6 9.5C6 12 8 15.4 12 20Z" fill="none" stroke="#f4b400" stroke-width="2" stroke-linejoin="round"/><circle cx="12" cy="9.5" r="2.1" fill="none" stroke="#f4b400" stroke-width="2"/></svg>',
            'globe' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><circle cx="12" cy="12" r="8" fill="none" stroke="#f4b400" stroke-width="2"/><path d="M4.5 12h15M12 4a12 12 0 0 1 0 16M12 4a12 12 0 0 0 0 16" fill="none" stroke="#f4b400" stroke-width="2" stroke-linecap="round"/></svg>',
        ];

        return invoice_svg_data_uri($svgMap[$type] ?? $svgMap['document']);
    }
}

$headerImageFile = trim((string) ($company_header_image ?? 'default-header.jpg'));
$headerImageSrc = invoice_data_uri('images/' . $headerImageFile);
$companyNameText = (string) ($company_name ?? 'EDI JAYA JAMBI');
$companySubtitleText = (string) ($company_subtitle ?? 'Penyewaan Alat Berat Terpercaya');
$companyDescText = (string) ($company_desc ?? 'Solusi terbaik untuk kebutuhan proyek Anda.');
$companyPhoneText = (string) ($company_phone ?? '+62 887-0707-7027');
$companyEmailText = (string) ($company_email ?? 'info@edijayajambi.com');
$companyLocationText = (string) ($company_location ?? 'Tanjung Mulya, Kec. Bahar Sel., Kabupaten Muaro Jambi, Jambi 30756');
$companyWebsiteText = (string) ($company_website ?? 'www.edijayajambi.com');
$brandLogoSrc = invoice_svg_data_uri('<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 72 72"><polygon points="36,6 58,18.5 58,43.5 36,56 14,43.5 14,18.5" fill="none" stroke="#f4b400" stroke-width="4" stroke-linejoin="round"/><rect x="24" y="37" width="16" height="4.6" rx="1.3" fill="#f4b400"/><rect x="28" y="30" width="8" height="7" rx="1.1" fill="#f4b400"/><circle cx="27" cy="44.8" r="2.6" fill="#f4b400"/><circle cx="38.5" cy="44.8" r="2.6" fill="#f4b400"/><path d="M32 30 L32 22.5 L43.5 17.5" fill="none" stroke="#f4b400" stroke-width="3.8" stroke-linecap="round" stroke-linejoin="round"/><path d="M43.5 17.5 L49.5 24.5" fill="none" stroke="#f4b400" stroke-width="3.8" stroke-linecap="round" stroke-linejoin="round"/><path d="M48.2 24.2 L44.8 27.2" fill="none" stroke="#f4b400" stroke-width="3.8" stroke-linecap="round" stroke-linejoin="round"/><path d="M22.5 41 L25 41" fill="none" stroke="#f4b400" stroke-width="3.8" stroke-linecap="round"/></svg>');
$brandLine1 = 'EDI JAYA';
$brandLine2 = 'JAMBI';
if (trim($companyNameText) !== '') {
    $normalizedCompanyName = preg_replace('/\s+/', ' ', trim($companyNameText));
    if (preg_match('/^(.*)\s+JAMBI$/i', $normalizedCompanyName, $matches)) {
        $brandLine1 = strtoupper(trim($matches[1]));
        $brandLine2 = 'JAMBI';
    } else {
        $parts = preg_split('/\s+/', $normalizedCompanyName);
        if (count($parts) > 1) {
            $brandLine2 = strtoupper((string) array_pop($parts));
            $brandLine1 = strtoupper(trim(implode(' ', $parts)));
        } else {
            $brandLine1 = strtoupper($normalizedCompanyName);
            $brandLine2 = '';
        }
    }
}
$invoiceNumber = '#' . ($pembayaran['id'] ?? '-');
$tanggalText = (string) ($pembayaran['created_at'] ?? date('Y-m-d H:i:s'));
$pemesanText = (string) ($pembayaran['nama_pemesan'] ?? ($pembayaran['email_pemesan'] ?? '-'));
$alatText = (string) ($pembayaran['nama_alat'] ?? '-');
$jamSewaText = !empty($lama_sewa) ? ((string) $lama_sewa . ' jam') : '-';
$bankPengirimText = (string) ($pembayaran['bank_pengirim'] ?? '-');
$namaPengirimText = (string) ($pembayaran['nama_pengirim'] ?? '-');
$bankTujuanText = (string) ($pembayaran['bank_tujuan'] ?? '-');
$biayaSewa = (int) ($pembayaran['jumlah'] ?? 0);
$deposit = (int) ($pembayaran['deposit'] ?? 0);
$total = $biayaSewa + $deposit;
$noteText = (string) ($note ?? 'Terima kasih telah menggunakan layanan kami. Kami siap membantu kebutuhan proyek Anda.');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Faktur Pembayaran</title>
    <style>
        @page {
            margin: 8px;
        }
        body {
            margin: 0;
            padding: 6px;
            font-family: DejaVu Sans, Arial, sans-serif;
            color: #1d2433;
            background: #f4f4f4;
            font-size: 11px;
        }
        .page {
            background: #ffffff;
            border: 1px solid #e5e5e5;
            border-radius: 10px;
            overflow: hidden;
        }
        .hero {
            width: 100%;
            border-collapse: collapse;
        }
        .hero td {
            vertical-align: middle;
            height: 118px;
        }
        .brand-cell {
            width: 36%;
            padding: 10px 12px;
            background: #ffffff;
        }
        .brand-table {
            width: 100%;
            border-collapse: collapse;
            height: 98px;
        }
        .brand-mark-wrap {
            width: 62px;
            vertical-align: middle;
        }
        .brand-logo {
            display: block;
            width: 52px;
            height: 52px;
        }
        .brand-name {
            font-size: 16px;
            line-height: 1.05;
            font-weight: bold;
            color: #0a1830;
            text-transform: uppercase;
            margin-bottom: 4px;
        }
        .brand-name .gold {
            color: #f4b400;
        }
        .brand-subtitle {
            margin-top: 4px;
            font-size: 7.6px;
            color: #5f6775;
            line-height: 1.45;
        }
        .banner-cell {
            width: 40%;
            background: #f1f1f1;
            padding: 0;
            overflow: hidden;
        }
        .banner-image {
            display: block;
            width: 100%;
            height: 118px;
            object-fit: cover;
        }
        .banner-placeholder {
            height: 118px;
            background: #d9dee7;
            color: #5f6775;
            text-align: center;
            line-height: 118px;
            font-size: 11px;
        }
        .invoice-cell {
            width: 24%;
            background: #07152d;
            color: #ffffff;
            text-align: center;
            padding: 12px 10px;
        }
        .invoice-title {
            font-size: 20px;
            font-weight: bold;
            letter-spacing: 1px;
            margin-bottom: 12px;
        }
        .invoice-number {
            display: inline-block;
            background: #f4b400;
            color: #07152d;
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 21px;
            font-weight: bold;
        }
        .section-pad {
            padding: 10px 12px 0;
        }
        .info-grid {
            width: 100%;
            border-collapse: collapse;
        }
        .info-grid td {
            width: 50%;
            vertical-align: top;
        }
        .info-box {
            padding: 10px 12px;
            border: 1px solid #ececec;
            background: #ffffff;
        }
        .info-box.left {
            border-right: none;
        }
        .box-title {
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
            color: #1a2334;
            margin-bottom: 6px;
        }
        .detail-table {
            width: 100%;
            border-collapse: collapse;
        }
        .detail-table td {
            padding: 5px 0;
            vertical-align: top;
        }
        .icon-col {
            width: 22px;
        }
        .icon-dot {
            display: inline-block;
            width: 16px;
            height: 16px;
            background: #f4b400;
            color: #ffffff;
            text-align: center;
            border-radius: 50%;
            vertical-align: middle;
        }
        .icon-dot img {
            display: block;
            width: 16px;
            height: 16px;
        }
        .label-col {
            width: 96px;
            color: #222a38;
            font-weight: bold;
        }
        .sep-col {
            width: 10px;
            text-align: center;
            color: #596274;
        }
        .value-col {
            color: #2c3442;
        }
        .invoice-table-wrap {
            padding: 10px 12px 0;
        }
        .invoice {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #e8d8a6;
            overflow: hidden;
        }
        .invoice th {
            padding: 9px 12px;
            background: #f4b400;
            color: #101828;
            text-transform: uppercase;
            font-size: 10px;
            text-align: left;
        }
        .invoice th.right,
        .invoice td.right {
            text-align: right;
        }
        .invoice td {
            padding: 10px 12px;
            border-top: 1px solid #e9e9e9;
            border-right: 1px solid #e9e9e9;
            background: #ffffff;
            font-size: 12px;
        }
        .invoice td:last-child,
        .invoice th:last-child {
            border-right: none;
        }
        .invoice tfoot td {
            background: #fbf2d8;
            font-size: 13px;
            font-weight: bold;
            color: #111827;
        }
        .thanks-wrap {
            padding: 12px;
        }
        .thanks-table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #ececec;
            background: #ffffff;
        }
        .thanks-table td {
            padding: 10px 12px;
            vertical-align: middle;
        }
        .thanks-left {
            width: 56%;
            border-right: 1px solid #ececec;
        }
        .shield {
            display: inline-block;
            width: 26px;
            height: 26px;
            vertical-align: middle;
        }
        .shield img {
            display: block;
            width: 26px;
            height: 26px;
        }
        .thanks-note {
            display: inline-block;
            margin-left: 8px;
            vertical-align: middle;
            width: 80%;
            color: #2d3443;
            line-height: 1.3;
            font-size: 10px;
        }
        .thanks-right {
            text-align: center;
        }
        .thanks-script {
            color: #d89b00;
            font-size: 17px;
            font-style: italic;
            margin-bottom: 4px;
        }
        .thanks-brand {
            font-size: 12px;
            font-weight: bold;
            color: #13203a;
        }
        .thanks-sub {
            font-size: 9px;
            color: #6b7280;
        }
        .footer-bar {
            width: 100%;
            border-collapse: collapse;
            background: #07152d;
            color: #ffffff;
        }
        .footer-bar td {
            width: 33.33%;
            padding: 10px 12px;
            font-size: 9.5px;
            text-align: center;
            border-right: 1px solid rgba(255, 255, 255, 0.08);
        }
        .footer-bar td:last-child {
            border-right: none;
        }
        .footer-icon {
            display: inline-block;
            width: 13px;
            height: 13px;
            vertical-align: -2px;
            margin-right: 5px;
        }
        .footer-icon img {
            display: block;
            width: 13px;
            height: 13px;
        }
    </style>
</head>
<body>
    <div class="page">
        <table class="hero">
            <tr>
                <td class="brand-cell">
                    <table class="brand-table">
                        <tr>
                            <td class="brand-mark-wrap">
                                <img src="<?= $brandLogoSrc ?>" alt="Logo Edi Jaya Jambi" class="brand-logo">
                            </td>
                            <td>
                                <div class="brand-name">
                                    <?= esc($brandLine1) ?><br>
                                    <?php if ($brandLine2 !== ''): ?>
                                        <span class="gold"><?= esc($brandLine2) ?></span>
                                    <?php endif; ?>
                                </div>
                                <div class="brand-subtitle">
                                    <?= esc($companySubtitleText) ?><br>
                                    <?= esc($companyDescText) ?>
                                </div>
                            </td>
                        </tr>
                    </table>
                </td>
                <td class="banner-cell">
                    <?php if ($headerImageSrc): ?>
                        <img src="<?= $headerImageSrc ?>" alt="Banner Faktur" class="banner-image">
                    <?php else: ?>
                        <div class="banner-placeholder">Banner Faktur</div>
                    <?php endif; ?>
                </td>
                <td class="invoice-cell">
                    <div class="invoice-title">FAKTUR</div>
                    <div class="invoice-number"><?= esc($invoiceNumber) ?></div>
                </td>
            </tr>
        </table>

        <div class="section-pad">
            <table class="info-grid">
                <tr>
                    <td>
                        <div class="info-box left">
                            <table class="detail-table">
                                <tr>
                                    <td class="icon-col"><span class="icon-dot"><img src="<?= invoice_info_icon('document') ?>" alt=""></span></td>
                                    <td class="label-col">Faktur #</td>
                                    <td class="sep-col">:</td>
                                    <td class="value-col"><?= esc($pembayaran['id'] ?? '-') ?></td>
                                </tr>
                                <tr>
                                    <td class="icon-col"><span class="icon-dot"><img src="<?= invoice_info_icon('calendar') ?>" alt=""></span></td>
                                    <td class="label-col">Tanggal</td>
                                    <td class="sep-col">:</td>
                                    <td class="value-col"><?= esc($tanggalText) ?></td>
                                </tr>
                                <tr>
                                    <td class="icon-col"><span class="icon-dot"><img src="<?= invoice_info_icon('user') ?>" alt=""></span></td>
                                    <td class="label-col">Pemesan</td>
                                    <td class="sep-col">:</td>
                                    <td class="value-col"><?= esc($pemesanText) ?></td>
                                </tr>
                                <tr>
                                    <td class="icon-col"><span class="icon-dot"><img src="<?= invoice_info_icon('truck') ?>" alt=""></span></td>
                                    <td class="label-col">Nama Alat</td>
                                    <td class="sep-col">:</td>
                                    <td class="value-col"><?= esc($alatText) ?></td>
                                </tr>
                                <tr>
                                    <td class="icon-col"><span class="icon-dot"><img src="<?= invoice_info_icon('clock') ?>" alt=""></span></td>
                                    <td class="label-col">Jam Sewa</td>
                                    <td class="sep-col">:</td>
                                    <td class="value-col"><?= esc($jamSewaText) ?></td>
                                </tr>
                            </table>
                        </div>
                    </td>
                    <td>
                        <div class="info-box">
                            <div class="box-title">Informasi Pengiriman</div>
                            <table class="detail-table">
                                <tr>
                                    <td class="icon-col"><span class="icon-dot"><img src="<?= invoice_info_icon('bank') ?>" alt=""></span></td>
                                    <td class="label-col">Bank Pengirim</td>
                                    <td class="sep-col">:</td>
                                    <td class="value-col"><?= esc($bankPengirimText) ?></td>
                                </tr>
                                <tr>
                                    <td class="icon-col"><span class="icon-dot"><img src="<?= invoice_info_icon('user') ?>" alt=""></span></td>
                                    <td class="label-col">Nama Pengirim</td>
                                    <td class="sep-col">:</td>
                                    <td class="value-col"><?= esc($namaPengirimText) ?></td>
                                </tr>
                                <tr>
                                    <td class="icon-col"><span class="icon-dot"><img src="<?= invoice_info_icon('bank') ?>" alt=""></span></td>
                                    <td class="label-col">Bank Tujuan</td>
                                    <td class="sep-col">:</td>
                                    <td class="value-col"><?= esc($bankTujuanText) ?></td>
                                </tr>
                            </table>
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        <div class="invoice-table-wrap">
            <table class="invoice">
                <thead>
                    <tr>
                        <th>Deskripsi</th>
                        <th class="right">Jumlah (Rp)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Biaya sewa</td>
                        <td class="right"><?= number_format($biayaSewa, 0, ',', '.') ?></td>
                    </tr>
                    <tr>
                        <td>Deposit</td>
                        <td class="right"><?= number_format($deposit, 0, ',', '.') ?></td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td>TOTAL</td>
                        <td class="right"><?= number_format($total, 0, ',', '.') ?></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="thanks-wrap">
            <table class="thanks-table">
                <tr>
                    <td class="thanks-left">
                        <span class="shield"><img src="<?= invoice_info_icon('shield-check') ?>" alt=""></span>
                        <span class="thanks-note"><?= esc($noteText) ?></span>
                    </td>
                    <td class="thanks-right">
                        <div class="thanks-script">Terima Kasih</div>
                        <div class="thanks-brand"><?= esc($companyNameText) ?></div>
                        <div class="thanks-sub">Partner terpercaya proyek Anda</div>
                    </td>
                </tr>
            </table>
        </div>

        <table class="footer-bar">
            <tr>
                <td><span class="footer-icon"><img src="<?= invoice_info_icon('phone') ?>" alt=""></span><?= esc($companyPhoneText) ?></td>
                <td><span class="footer-icon"><img src="<?= invoice_info_icon('location') ?>" alt=""></span><?= esc($companyLocationText) ?></td>
                <td><span class="footer-icon"><img src="<?= invoice_info_icon('globe') ?>" alt=""></span><?= esc($companyWebsiteText) ?></td>
            </tr>
        </table>
    </div>
</body>
</html>
