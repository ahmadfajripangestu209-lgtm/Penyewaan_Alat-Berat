<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>
<?php
$laporanMingguan = $laporan_mingguan ?? null;
$laporanBulanan = $laporan_bulanan ?? null;
?>
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-12 text-center">
            <h2 class="fw-bold mb-2">Dashboard Admin</h2>
            <p class="lead">Selamat datang, <b><?= esc(session('user_name')) ?></b>!</p>
            <p>Email: <?= esc(session('user_email')) ?> <br>Role: <span class="badge bg-warning text-dark">Admin</span></p>
        </div>
    </div>
    <div class="row g-4 mb-5 justify-content-center">
        <div class="col-md-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body text-center">
                    <div class="mb-2">
                        <i class="fas fa-users fa-2x text-primary"></i>
                    </div>
                    <h4 class="fw-bold mb-1">Total Pengguna</h4>
                    <div class="display-5 fw-bold text-primary mb-2"><?= $total_user ?? 0 ?></div>
                    <a href="<?= base_url('admin/users') ?>" class="btn btn-outline-primary btn-sm">Manajemen Pengguna</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body text-center">
                    <div class="mb-2">
                        <i class="fas fa-truck-monster fa-2x text-warning"></i>
                    </div>
                    <h4 class="fw-bold mb-1">Total Alat Berat</h4>
                    <div class="display-5 fw-bold text-warning mb-2"><?= $total_alat ?? 0 ?></div>
                    <a href="<?= base_url('admin/alat-berat') ?>" class="btn btn-outline-warning btn-sm">Manajemen Alat Berat</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body text-center">
                    <div class="mb-2">
                        <i class="fas fa-file-invoice fa-2x text-success"></i>
                    </div>
                    <h4 class="fw-bold mb-1">Total Penawaran</h4>
                    <div class="display-5 fw-bold text-success mb-2"><?= $total_kuotasi ?? 0 ?></div>
                    <a href="<?= base_url('admin/penawaran') ?>" class="btn btn-outline-success btn-sm">Kelola Penawaran</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body text-center">
                    <div class="mb-2">
                        <i class="fas fa-hand-holding-usd fa-2x text-success"></i>
                    </div>
                    <h4 class="fw-bold mb-1">Total Bayar</h4>
                    <div class="display-6 fw-bold text-success mb-2">Rp <?= number_format($total_bayar ?? 0, 0, ',', '.') ?></div>
                    <div class="small text-muted">
                        Pending: Rp <?= number_format($total_bayar_pending ?? 0, 0, ',', '.') ?> · Ditolak: Rp <?= number_format($total_bayar_rejected ?? 0, 0, ',', '.') ?>
                    </div>
                    <a href="<?= base_url('admin/pembayaran') ?>" class="btn btn-outline-success btn-sm">Kelola Pembayaran</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body text-center">
                    <div class="mb-2">
                        <i class="fas fa-stopwatch fa-2x text-primary"></i>
                    </div>
                    <h4 class="fw-bold mb-1">Durasi sewa Alat Berat</h4>
                    <?php if (!empty($active_sewa_count) && $active_sewa_count > 0): ?>
                        <div class="display-6 fw-bold text-primary mb-2">
                            <?= esc($active_sewa_count) ?> sewa aktif
                        </div>
                    <?php else: ?>
                        <div class="display-6 fw-bold text-primary mb-2">Tidak ada sewa aktif</div>
                    <?php endif; ?>
                    <a href="<?= base_url('admin/durasi-sewa') ?>" class="btn btn-outline-primary btn-sm">Kelola Timer</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body text-center">
                    <div class="mb-2">
                        <i class="fas fa-chart-line fa-2x text-info"></i>
                    </div>
                    <h4 class="fw-bold mb-1">Laporan Pembayaran</h4>
                    <div class="mb-2">
                        <div class="small text-muted">Minggu ini</div>
                        <div class="fw-bold text-success">
                            Rp <?= number_format($laporanMingguan['total_pendapatan'] ?? 0, 0, ',', '.') ?>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="small text-muted">Bulan ini</div>
                        <div class="fw-bold text-primary">
                            Rp <?= number_format($laporanBulanan['total_pendapatan'] ?? 0, 0, ',', '.') ?>
                        </div>
                    </div>
                    <a href="<?= base_url('admin/laporan-pembayaran') ?>" class="btn btn-outline-info btn-sm">Lihat Laporan</a>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?> 
