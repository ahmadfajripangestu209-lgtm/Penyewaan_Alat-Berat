<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>
<div class="container my-5">
    <!-- Header dengan gradient background -->
    <div class="bg-gradient-primary rounded-3 p-4 mb-4 shadow-sm">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="fw-bold text-white mb-1">
                    <i class="fas fa-history me-2" style="color: #000 !important;"></i>Riwayat sewa alat berat
                </h2>
                <p class="text-dark mb-0">Kelola semua permintaan sewa alat berat Anda</p>
            </div>
            <div class="text-dark">
                <i class="fas fa-chart-line fa-2x" style="color: #fff !important;"></i>
            </div>
        </div>
    </div>

    <?php if (session('message')): ?>
        <div class="alert alert-success border-0 shadow-sm">
            <i class="fas fa-check-circle me-2"></i><?= session('message') ?>
        </div>
    <?php endif; ?>

    <?php if (session('error')): ?>
        <div class="alert alert-danger border-0 shadow-sm">
            <i class="fas fa-exclamation-circle me-2"></i><?= session('error') ?>
        </div>
    <?php endif; ?>

    <?php if (empty($riwayat)): ?>
        <div class="text-center py-5">
            <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 100px; height: 100px;">
                <i class="fas fa-inbox fa-3x text-muted"></i>
            </div>
            <h5 class="text-muted mb-2">Belum ada permintaan penawaran</h5>
            <p class="text-muted">Mulai dengan mengajukan penawaran untuk alat berat yang Anda butuhkan</p>
            <a href="<?= base_url('alat-berat') ?>" class="btn btn-primary">
                <i class="fas fa-search me-2"></i>Lihat Alat Berat
            </a>
        </div>
    <?php else: ?>
        <div class="row">
            <?php foreach($riwayat as $i => $r): ?>
                <div class="col-lg-6 col-xl-4 mb-4">
                    <div class="card border-0 h-100 shadow-hover" style="transition: all 0.3s ease;">
                        <div class="card-header bg-gradient-light border-0 rounded-top">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0 fw-bold text-primary">#<?= $i+1 ?> - <?= esc($r['nama_alat']) ?></h6>
                                    <small class="text-muted"><?= esc($r['nama_lengkap_alat']) ?></small>
                                </div>
                                <?php if (!empty($r['lunas'])): ?>
                                    <span class="badge bg-success fs-6 px-3 py-2">
                                        <i class="fas fa-check-circle me-1"></i>Lunas
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-<?= $r['status']==='pending'?'secondary':($r['status']==='diterima'?'success':'danger') ?> fs-6 px-3 py-2">
                                        <i class="fas fa-<?= $r['status']==='pending'?'clock':($r['status']==='diterima'?'check':'times') ?> me-1"></i>
                                        <?= ucfirst($r['status']) ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-6">
                                    <small class="text-muted fw-semibold">
                                        <i class="fas fa-envelope me-1"></i>Email
                                    </small>
                                    <div class="small text-truncate"><?= esc($r['email_pengunjung'] ?? $r['user_email'] ?? '-') ?></div>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted fw-semibold">
                                        <i class="fas fa-phone me-1"></i>No Telepon
                                    </small>
                                    <div><?= esc($r['telepon_pengunjung'] ?? '-') ?></div>
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-6">
                                    <small class="text-muted fw-semibold">
                                        <i class="fas fa-money-bill-wave me-1"></i>Harga Ditawarkan
                                    </small>
                                    <div class="fw-bold text-warning">
                                        <?php if ($r['status'] === 'ditolak'): ?>
                                            <?php 
                                            $jumlahAjukanUlang = $r['jumlah_ajukan_ulang'] ?? 0;
                                            $sisaKesempatan = 3 - $jumlahAjukanUlang;
                                            ?>
                                            <?php if ($sisaKesempatan > 0): ?>
                                                <form action="<?= base_url('alat-berat/riwayat-penawaran/ajukan-ulang/' . $r['id']) ?>" method="post" class="mb-2">
                                                    <input type="number" name="harga_ditawarkan" class="form-control form-control-sm" min="0" step="1" value="<?= esc($r['harga_ditawarkan']) ?>" required>
                                                    <button type="submit" class="btn btn-sm btn-primary w-100 mt-1">
                                                        <i class="fas fa-redo me-1"></i>Ajukan Ulang
                                                    </button>
                                                </form>
                                                <small class="text-muted">
                                                    <i class="fas fa-info-circle me-1"></i>Sisa: <?= $sisaKesempatan ?> kali
                                                </small>
                                            <?php else: ?>
                                                <span class="text-danger fw-bold">
                                                    <i class="fas fa-exclamation-triangle me-1"></i>Batas maksimal tercapai
                                                </span>
                                                <br><small class="text-muted">Hubungi admin</small>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <?= $r['harga_ditawarkan'] ? 'Rp ' . (is_numeric($r['harga_ditawarkan']) ? number_format((float)$r['harga_ditawarkan'], 0, ',', '.') : $r['harga_ditawarkan']) : '-' ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted fw-semibold">
                                        <i class="fas fa-calendar-alt me-1"></i>Tanggal Permintaan
                                    </small>
                                    <div class="small">
                                        <?php
                                        $dt = new DateTime($r['tanggal_minta'], new DateTimeZone('UTC'));
                                        $dt->setTimezone(new DateTimeZone('Asia/Jakarta'));
                                        echo $dt->format('d-m-Y H:i') . ' WIB';
                                        ?>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-12">
                                    <small class="text-muted fw-semibold">
                                        <i class="fas fa-calculator me-1"></i>Total Estimasi Harga
                                    </small>
                                    <div class="fw-bold text-success">
                                        <?= isset($r['total_estimasi']) && is_numeric($r['total_estimasi']) ? 'Rp ' . number_format((float)$r['total_estimasi'], 0, ',', '.') : '-' ?>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <small class="text-muted fw-semibold">
                                    <i class="fas fa-map-marker-alt me-1"></i>Alamat Pengiriman
                                </small>
                                <div class="small"><?= esc($r['alamat_pengiriman'] ?? '-') ?></div>
                            </div>

                            <?php if (!empty($r['catatan'])): ?>
                                <div class="mb-3">
                                    <small class="text-muted fw-semibold">
                                        <i class="fas fa-sticky-note me-1"></i>Catatan
                                    </small>
                                    <div class="small bg-light p-2 rounded"><?= esc($r['catatan']) ?></div>
                                </div>
                            <?php endif; ?>

                            <?php if (!empty($r['pesan_admin'])): ?>
                                <div class="mb-3">
                                    <small class="text-muted fw-semibold">
                                        <i class="fas fa-comment-dots me-1"></i>Pesan Admin
                                    </small>
                                    <div class="bg-warning bg-opacity-10 p-3 rounded border-start border-warning border-3">
                                        <?= nl2br(esc($r['pesan_admin'])) ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                            
                            <?php if (!empty($r['alasan_penolakan']) && empty($r['lunas'])): ?>
                                <div class="mb-3">
                                    <small class="text-muted fw-semibold">
                                        <i class="fas fa-times-circle me-1"></i>Alasan Penolakan Pembayaran
                                    </small>
                                    <div class="bg-danger bg-opacity-10 p-3 rounded border-start border-danger border-3">
                                        <?= nl2br(esc($r['alasan_penolakan'])) ?>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <div class="border-top pt-3">
                                <div class="d-flex gap-2">
                                    <form action="<?= base_url('alat-berat/riwayat-penawaran/hapus/' . $r['id']) ?>" method="post" onsubmit="return confirm('Yakin ingin menghapus permintaan penawaran ini?');" class="flex-fill">
                                        <button type="submit" class="btn btn-sm btn-outline-danger w-100">
                                            <i class="fas fa-trash me-1"></i>Hapus
                                        </button>
                                    </form>
                                    <?php if (!empty($r['pembayaran_pending'])): ?>
                                        <span class="text-muted small">
                                            <i class="fas fa-clock me-1"></i>Pembayaran menunggu verifikasi admin
                                        </span>
                                    <?php elseif ($r['status'] === 'diterima' && empty($r['lunas']) && (($r['pembayaran_rejected_count'] ?? 0) < 3)): ?>
                                        <a href="<?= base_url('pembayaran/' . $r['id']) ?>" class="btn btn-sm btn-success">
                                            <i class="fas fa-money-bill-wave me-1"></i><?= !empty($r['pembayaran_rejected']) ? 'Bayar Ulang' : 'Bayar' ?>
                                        </a>
                                    <?php endif; ?>
                                    <?php if (($r['pembayaran_rejected_count'] ?? 0) >= 3 && empty($r['lunas'])): ?>
                                        <span class="text-danger small">
                                            <i class="fas fa-exclamation-triangle me-1"></i>Batas bayar ulang tercapai (3x). Hubungi admin.
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<style>
.bg-gradient-primary {
    background: linear-gradient(135deg, #ffc107 0%, #212529 100%);
}

.bg-gradient-light {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
}

.shadow-hover:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.15) !important;
}

.card {
    border-radius: 15px;
    overflow: hidden;
}

.card-header {
    border-radius: 15px 15px 0 0 !important;
}

.badge {
    border-radius: 20px;
}
</style>
<?= $this->endSection() ?> 
