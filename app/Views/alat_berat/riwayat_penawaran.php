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

                            <?php
                                $deposit = isset($r['pembayaran_deposit']) && is_numeric($r['pembayaran_deposit']) ? (int)$r['pembayaran_deposit'] : 3000000;
                                $totalBayar = (isset($r['total_estimasi']) && is_numeric($r['total_estimasi']) ? (int)$r['total_estimasi'] : 0) + $deposit;
                                $rentalEndAt = !empty($r['pembayaran_rental_end_at']) ? $r['pembayaran_rental_end_at'] : null;
                            ?>
                            <div class="row mb-3">
                                <div class="col-md-6 mb-3 mb-md-0">
                                    <div class="card border-0 shadow-sm h-100">
                                        <div class="card-body p-3">
                                            <small class="text-muted fw-semibold">
                                                <i class="fas fa-calculator me-1"></i>Total Bayar
                                            </small>
                                            <div class="fw-bold text-success fs-5 mt-2">
                                                <?= 'Rp ' . number_format($totalBayar, 0, ',', '.') ?>
                                            </div>
                                            <div class="small text-muted mt-1">Termasuk estimasi sewa dan uang jaminan</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <?php if (!empty($rentalEndAt) && isset($r['pembayaran_status']) && $r['pembayaran_status'] === 'verified'): ?>
                                        <div class="card border-0 shadow-sm h-100 bg-success bg-opacity-10">
                                            <div class="card-body p-3">
                                                <small class="text-muted fw-semibold">
                                                    <i class="fas fa-clock me-1"></i>Sisa Waktu Sewa
                                                </small>
                                                <div class="h4 fw-bold text-success mt-2 countdown-timer" id="countdown-<?= esc($r['id']) ?>" data-end-unix="<?= esc(strtotime($rentalEndAt)) ?>">
                                                    --:--:--
                                                </div>
                                                <div class="small text-muted mt-1">Berakhir pada <?= date('d-m-Y H:i', strtotime($rentalEndAt)) ?> WIB</div>
                                            </div>
                                        </div>
                                    <?php elseif (isset($r['pembayaran_status']) && $r['pembayaran_status'] === 'verified'): ?>
                                        <div class="alert alert-info small mb-0">
                                            Waktu sewa sedang diproses. Harap tunggu data rental selesai.
                                        </div>
                                    <?php endif; ?>
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
                            
                            <?php if (isset($r['pembayaran_status'])): ?>
                                <div class="mb-3">
                                    <small class="text-muted fw-semibold">
                                        <i class="fas fa-receipt me-1"></i>Rincian Pembayaran
                                    </small>
                                    <div class="bg-light p-3 rounded">
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Status</span>
                                            <?php 
                                                $status = $r['pembayaran_status'];
                                                $badge = $status==='verified'?'success':($status==='rejected'?'danger':'secondary');
                                            ?>
                                            <span class="badge bg-<?= $badge ?>"><?= ucfirst($status) ?></span>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <span>Biaya Sewa</span>
                                            <span class="fw-bold"><?= isset($r['biaya_sewa']) ? 'Rp ' . number_format((int)$r['biaya_sewa'], 0, ',', '.') : '-' ?></span>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <span>Biaya Operator</span>
                                            <span class="fw-bold"><?= isset($r['biaya_operator']) ? 'Rp ' . number_format((int)$r['biaya_operator'], 0, ',', '.') : '-' ?></span>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <span>Uang Jaminan</span>
                                            <span class="fw-bold"><?= isset($r['pembayaran_deposit']) ? 'Rp ' . number_format((int)$r['pembayaran_deposit'], 0, ',', '.') : '-' ?></span>
                                        </div>
                                        <hr>
                                        <div class="row">
                                            <div class="col-6 small">
                                                <div class="text-muted">Bank Pengirim</div>
                                                <div><?= esc($r['pembayaran_bank_pengirim'] ?? '-') ?></div>
                                            </div>
                                            <div class="col-6 small">
                                                <div class="text-muted">Nama Pengirim</div>
                                                <div><?= esc($r['pembayaran_nama_pengirim'] ?? '-') ?></div>
                                            </div>
                                        </div>
                                        <div class="small mt-2">
                                            <span class="text-muted">Bank Tujuan:</span> <?= esc($r['pembayaran_bank_tujuan'] ?? '-') ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <?php
                                $rentalEnded = !empty($rentalEndAt) && (strtotime($rentalEndAt) < time());
                            ?>
                            <div class="border-top pt-3">
                                <div class="d-flex gap-2">
                                    <div id="delete-<?= esc($r['id']) ?>" class="flex-fill" style="display: <?= $rentalEnded ? 'block' : 'none' ?>;">
                                        <form action="<?= base_url('alat-berat/riwayat-penawaran/hapus/' . $r['id']) ?>" method="post" onsubmit="return confirm('Yakin ingin menghapus permintaan penawaran ini?');" class="mb-0">
                                            <button type="submit" class="btn btn-sm btn-outline-danger w-100">
                                                <i class="fas fa-trash me-1"></i>Hapus
                                            </button>
                                        </form>
                                    </div>
                                    <?php if (!empty($r['pembayaran_pending'])): ?>
                                        <span class="text-muted small">
                                            <i class="fas fa-clock me-1"></i>Pembayaran menunggu verifikasi admin
                                        </span>
                                    <?php elseif ($r['status'] === 'diterima' && empty($r['lunas']) && (($r['pembayaran_rejected_count'] ?? 0) < 3)): ?>
                                        <a href="<?= base_url('pembayaran/' . $r['id']) ?>" class="btn btn-sm btn-success">
                                            <i class="fas fa-money-bill-wave me-1"></i><?= !empty($r['pembayaran_rejected']) ? 'Bayar Ulang' : 'Bayar' ?>
                                        </a>
                                    <?php endif; ?>
                                    <?php if (!empty($r['pembayaran_id']) && !empty($r['pembayaran_status']) && $r['pembayaran_status'] === 'verified'): ?>
                                        <a href="<?= base_url('pembayaran/invoice/' . $r['pembayaran_id']) ?>" target="_blank" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-file-pdf me-1"></i>Unduh Faktur
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

<script>
    function formatCountdown(seconds) {
        if (seconds <= 0) return '00:00:00';
        const h = Math.floor(seconds / 3600).toString().padStart(2, '0');
        const m = Math.floor((seconds % 3600) / 60).toString().padStart(2, '0');
        const s = Math.floor(seconds % 60).toString().padStart(2, '0');
        return h + ':' + m + ':' + s;
    }

    function initCountdownTimers() {
        document.querySelectorAll('.countdown-timer').forEach(el => {
            const endUnix = parseInt(el.dataset.endUnix, 10);
            if (!endUnix || isNaN(endUnix)) return;
            let intervalId;
            function update() {
                const now = Math.floor(Date.now() / 1000);
                const diff = endUnix - now;
                const deleteId = 'delete-' + (el.id || '').replace('countdown-', '');
                if (diff <= 0) {
                    el.textContent = 'Waktu sewa selesai';
                    const delEl = document.getElementById(deleteId);
                    if (delEl) delEl.style.display = 'block';
                    clearInterval(intervalId);
                    return;
                }
                el.textContent = formatCountdown(diff);
            }
            update();
            intervalId = setInterval(update, 1000);
        });
    }

    document.addEventListener('DOMContentLoaded', initCountdownTimers);
</script>
<?= $this->endSection() ?> 
