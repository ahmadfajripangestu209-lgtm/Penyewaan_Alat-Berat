<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>
<?php
if (!function_exists('format_tanggal_indo')) {
    function format_tanggal_indo($tanggal)
    {
        if (empty($tanggal)) {
            return '-';
        }
        $ts = strtotime($tanggal);
        if (!$ts) {
            return '-';
        }
        $bulan = [
            '01' => 'Januari',
            '02' => 'Februari',
            '03' => 'Maret',
            '04' => 'April',
            '05' => 'Mei',
            '06' => 'Juni',
            '07' => 'Juli',
            '08' => 'Agustus',
            '09' => 'September',
            '10' => 'Oktober',
            '11' => 'November',
            '12' => 'Desember',
        ];
        $bulanIndex = date('m', $ts);
        $namaBulan = $bulan[$bulanIndex] ?? date('F', $ts);
        return date('j', $ts) . ' ' . $namaBulan . ' ' . date('Y', $ts);
    }
}
$laporanMingguan = $laporan_mingguan ?? null;
$laporanBulanan = $laporan_bulanan ?? null;
?>
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-12 text-center">
            <h2 class="fw-bold mb-2 text-uppercase">Laporan Pembayaran</h2>
            <p class="lead mb-0"><?= esc($nama_usaha ?? 'EDI JAYA JAMBI') ?></p>
            <div class="mt-3 no-print">
                <form class="row g-2 justify-content-center align-items-end" method="get" action="<?= base_url('admin/laporan-pembayaran') ?>">
                    <div class="col-sm-4 col-md-3">
                        <label class="form-label mb-1">Mingguan - Tanggal awal minggu</label>
                        <input type="date" name="week_start" value="<?= esc($filter_week_start ?? '') ?>" class="form-control form-control-sm">
                    </div>
                    <div class="col-sm-4 col-md-3">
                        <label class="form-label mb-1">Bulanan - Pilih bulan</label>
                        <input type="month" name="month" value="<?= esc($filter_month ?? '') ?>" class="form-control form-control-sm">
                    </div>
                    <div class="col-sm-4 col-md-3 d-flex gap-2">
                        <button type="submit" class="btn btn-primary btn-sm flex-fill">
                            <i class="fas fa-filter me-1"></i> Terapkan Filter
                        </button>
                        <button type="button" onclick="window.print()" class="btn btn-outline-secondary btn-sm flex-fill">
                            <i class="fas fa-print me-1"></i> Cetak / PDF
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h4 class="fw-bold text-center text-uppercase text-primary mb-3">Laporan Pembayaran Mingguan</h4>
                    <div class="d-flex flex-column flex-md-row justify-content-between mb-3">
                        <div><strong>Nama Usaha:</strong> <?= esc($nama_usaha ?? 'EDI JAYA JAMBI') ?></div>
                        <div>
                            <strong>Periode:</strong>
                            <?php if (!empty($laporanMingguan['periode_mulai']) && !empty($laporanMingguan['periode_selesai'])): ?>
                                <?= format_tanggal_indo($laporanMingguan['periode_mulai']) ?> - <?= format_tanggal_indo($laporanMingguan['periode_selesai']) ?>
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </div>
                    </div>
                    <h6 class="fw-bold mb-2">1. Rincian Transaksi</h6>
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
                            <thead class="table-primary">
                                <tr class="text-center">
                                    <th>No</th>
                                    <th>Tanggal</th>
                                    <th>Nama Pelanggan</th>
                                    <th>Jenis Alat</th>
                                    <th>Lama Sewa</th>
                                    <th>Total Bayar</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($laporanMingguan['transaksi'])): ?>
                                    <?php $no = 1; ?>
                                    <?php foreach ($laporanMingguan['transaksi'] as $t): ?>
                                        <tr>
                                            <td class="text-center"><?= $no++ ?></td>
                                            <td><?= format_tanggal_indo(substr($t['created_at'], 0, 10)) ?></td>
                                            <td><?= esc($t['nama_pemesan'] ?? $t['nama_pengirim'] ?? '-') ?></td>
                                            <td><?= esc($t['jenis_alat'] ?? $t['nama_alat'] ?? '-') ?></td>
                                            <td>
                                                <?php
                                                    $lama = $t['lama_sewa'] ?? null;
                                                    if ($lama) {
                                                        echo esc($lama) . ' jam';
                                                    } else {
                                                        echo '-';
                                                    }
                                                ?>
                                            </td>
                                            <td>Rp <?= number_format((int)($t['jumlah'] ?? 0), 0, ',', '.') ?></td>
                                            <td>
                                                <?php
                                                    $status = $t['status'] ?? '';
                                                    if ($status === 'verified') {
                                                        echo 'Lunas';
                                                    } elseif ($status === 'pending') {
                                                        echo 'Menunggu';
                                                    } elseif ($status === 'rejected') {
                                                        echo 'Ditolak';
                                                    } else {
                                                        echo strtoupper($status);
                                                    }
                                                ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="7" class="text-center text-muted">Belum ada pembayaran terverifikasi pada periode ini.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        <div>• Total Transaksi: <b><?= $laporanMingguan['total_transaksi'] ?? 0 ?></b></div>
                        <div>• Total Pendapatan Mingguan: <b>Rp <?= number_format($laporanMingguan['total_pendapatan'] ?? 0, 0, ',', '.') ?></b></div>
                        <div>• Total Denda: <b>Rp <?= number_format($laporanMingguan['total_denda'] ?? 0, 0, ',', '.') ?></b></div>
                        <div>• Total Bersih: <b>Rp <?= number_format($laporanMingguan['total_bersih'] ?? 0, 0, ',', '.') ?></b></div>
                    </div>
                    <p class="mt-3 mb-0 small text-muted">
                        Pada minggu ini, terdapat <?= $laporanMingguan['total_transaksi'] ?? 0 ?> transaksi pembayaran dengan total pendapatan
                        sebesar Rp <?= number_format($laporanMingguan['total_pendapatan'] ?? 0, 0, ',', '.') ?>.
                    </p>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h4 class="fw-bold text-center text-uppercase text-primary mb-3">Laporan Pembayaran Bulanan</h4>
                    <div class="d-flex flex-column flex-md-row justify-content-between mb-3">
                        <div><strong>Nama Usaha:</strong> <?= esc($nama_usaha ?? 'EDI JAYA JAMBI') ?></div>
                        <div>
                            <strong>Periode:</strong>
                            <?php if (!empty($laporanBulanan['periode_mulai']) && !empty($laporanBulanan['periode_selesai'])): ?>
                                <?= format_tanggal_indo($laporanBulanan['periode_mulai']) ?> - <?= format_tanggal_indo($laporanBulanan['periode_selesai']) ?>
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </div>
                    </div>
                    <h6 class="fw-bold mb-2">1. Rincian Transaksi</h6>
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
                            <thead class="table-primary">
                                <tr class="text-center">
                                    <th>No</th>
                                    <th>Tanggal</th>
                                    <th>Nama Pelanggan</th>
                                    <th>Jenis Alat</th>
                                    <th>Lama Sewa</th>
                                    <th>Total Bayar</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($laporanBulanan['transaksi'])): ?>
                                    <?php $no = 1; ?>
                                    <?php foreach ($laporanBulanan['transaksi'] as $t): ?>
                                        <tr>
                                            <td class="text-center"><?= $no++ ?></td>
                                            <td><?= format_tanggal_indo(substr($t['created_at'], 0, 10)) ?></td>
                                            <td><?= esc($t['nama_pemesan'] ?? $t['nama_pengirim'] ?? '-') ?></td>
                                            <td><?= esc($t['jenis_alat'] ?? $t['nama_alat'] ?? '-') ?></td>
                                            <td>
                                                <?php
                                                    $lama = $t['lama_sewa'] ?? null;
                                                    if ($lama) {
                                                        echo esc($lama) . ' jam';
                                                    } else {
                                                        echo '-';
                                                    }
                                                ?>
                                            </td>
                                            <td>Rp <?= number_format((int)($t['jumlah'] ?? 0), 0, ',', '.') ?></td>
                                            <td>
                                                <?php
                                                    $status = $t['status'] ?? '';
                                                    if ($status === 'verified') {
                                                        echo 'Lunas';
                                                    } elseif ($status === 'pending') {
                                                        echo 'Menunggu';
                                                    } elseif ($status === 'rejected') {
                                                        echo 'Ditolak';
                                                    } else {
                                                        echo strtoupper($status);
                                                    }
                                                ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="7" class="text-center text-muted">Belum ada pembayaran terverifikasi pada periode ini.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        <div>• Total Transaksi: <b><?= $laporanBulanan['total_transaksi'] ?? 0 ?></b></div>
                        <div>• Total Pendapatan Bulanan: <b>Rp <?= number_format($laporanBulanan['total_pendapatan'] ?? 0, 0, ',', '.') ?></b></div>
                        <div>• Total Denda: <b>Rp <?= number_format($laporanBulanan['total_denda'] ?? 0, 0, ',', '.') ?></b></div>
                        <div>• Total Bersih: <b>Rp <?= number_format($laporanBulanan['total_bersih'] ?? 0, 0, ',', '.') ?></b></div>
                    </div>
                    <p class="mt-3 mb-0 small text-muted">
                        Pada bulan ini, terdapat <?= $laporanBulanan['total_transaksi'] ?? 0 ?> transaksi pembayaran dengan total pendapatan
                        sebesar Rp <?= number_format($laporanBulanan['total_pendapatan'] ?? 0, 0, ',', '.') ?>.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
