<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-dark">
            <i class="fas fa-money-bill-wave me-2 text-info"></i>Kelola Pembayaran
        </h2>
        <form action="<?= base_url('admin/pembayaran/hapus-semua') ?>" method="post" onsubmit="return confirm('Hapus semua data pembayaran? Tindakan ini tidak dapat dibatalkan.');">
            <button type="submit" class="btn btn-outline-danger">
                <i class="fas fa-trash-alt me-1"></i>Hapus Semua
            </button>
        </form>
    </div>

    <?php if (session('message')): ?>
        <div class="alert alert-success border-0 shadow-sm mb-4">
            <i class="fas fa-check-circle me-2"></i><?= session('message') ?>
        </div>
    <?php endif; ?>

    <?php if (empty($pembayaran)): ?>
        <div class="text-center py-5 bg-light rounded-3">
            <div class="mb-3">
                <i class="fas fa-file-invoice-dollar fa-3x text-muted"></i>
            </div>
            <h5 class="text-muted">Belum ada data pembayaran</h5>
        </div>
    <?php else: ?>
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="py-3 px-4">ID</th>
                                <th class="py-3 px-4">Tanggal</th>
                                <th class="py-3 px-4">Pemesan</th>
                                <th class="py-3 px-4">Alat Berat</th>
                                <th class="py-3 px-4">Jumlah</th>
                                <th class="py-3 px-4">Deposit</th>
                                <th class="py-3 px-4">Info Pengirim</th>
                                <th class="py-3 px-4">Bukti</th>
                                <th class="py-3 px-4">Status</th>
                                <th class="py-3 px-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($pembayaran as $p): ?>
                                <tr>
                                    <td class="px-4">#<?= $p['id'] ?></td>
                                    <td class="px-4 text-muted small">
                                        <?= isset($p['created_at']) ? date('d M Y H:i', strtotime($p['created_at'])) : '-' ?>
                                    </td>
                                    <td class="px-4">
                                        <div class="fw-bold"><?= esc($p['nama_pemesan'] ?? ($p['nama_pengirim'] ?? '-')) ?></div>
                                        <small class="text-muted"><?= esc($p['email_pemesan'] ?? '-') ?></small>
                                    </td>
                                    <td class="px-4"><?= esc($p['nama_alat'] ?? '-') ?></td>
                                    <td class="px-4 fw-bold text-primary">
                                        Rp <?= number_format((int)($p['jumlah'] ?? 0), 0, ',', '.') ?>
                                    </td>
                                    <td class="px-4 fw-bold text-secondary">
                                        Rp <?= number_format((int)($p['deposit'] ?? 0), 0, ',', '.') ?>
                                    </td>
                                    <td class="px-4 small">
                                        <div><strong><?= esc($p['bank_pengirim'] ?? '-') ?></strong></div>
                                        <div>a.n. <?= esc($p['nama_pengirim'] ?? '-') ?></div>
                                        <div class="text-muted">ke: <?= esc($p['bank_tujuan'] ?? '-') ?></div>
                                    </td>
                                    <td class="px-4">
                                        <?php if (!empty($p['bukti_transfer'])): ?>
                                        <a href="<?= base_url('uploads/bukti_transfer/' . $p['bukti_transfer']) ?>" target="_blank" class="btn btn-sm btn-outline-secondary">
                                            <i class="fas fa-image me-1"></i>Lihat
                                        </a>
                                        <?php else: ?>
                                        <span class="text-muted small">Tidak ada</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-4">
                                        <?php $status = $p['status'] ?? 'pending'; ?>
                                        <?php if ($status == 'pending'): ?>
                                            <span class="badge bg-warning text-dark">Pending</span>
                                        <?php elseif ($status == 'verified'): ?>
                                            <span class="badge bg-success">Terverifikasi</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger">Ditolak</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-4 text-center">
                                        <div class="d-flex justify-content-center gap-2">
                                            <?php if ($status == 'pending'): ?>
                                                <form action="<?= base_url('admin/pembayaran/verifikasi/' . $p['id']) ?>" method="post" onsubmit="return confirm('Verifikasi pembayaran ini?');">
                                                    <button type="submit" class="btn btn-sm btn-success" title="Verifikasi">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                </form>
                                                <form action="<?= base_url('admin/pembayaran/tolak/' . $p['id']) ?>" method="post" onsubmit="return confirm('Tolak pembayaran ini?');" class="d-flex align-items-center gap-2">
                                                    <input type="text" name="alasan_penolakan" class="form-control form-control-sm" placeholder="Alasan penolakan" style="max-width: 200px;">
                                                    <button type="submit" class="btn btn-sm btn-danger" title="Tolak">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </form>
                                            <?php endif; ?>
                                            <?php if ($status == 'verified'): ?>
                                                <button type="button" class="btn btn-sm btn-info btn-dipinjam" data-id="<?= $p['id'] ?>" title="Tandai Dipinjam">
                                                    <i class="fas fa-hand-holding"></i> Dipinjam
                                                </button>
                                            <?php endif; ?>
                                            <form action="<?= base_url('admin/pembayaran/hapus/' . $p['id']) ?>" method="post" onsubmit="return confirm('Hapus pembayaran ini secara permanen?');">
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.btn-dipinjam').forEach(function(btn){
        btn.addEventListener('click', function(){
            var id = this.getAttribute('data-id');
            if (!confirm('Tandai alat terkait pembayaran ini sebagai Dipinjam?')) return;
            fetch('<?= base_url('admin/pembayaran/dipinjam/') ?>' + id, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json'
                }
            }).then(function(res){
                return res.json();
            }).then(function(data){
                if (data && data.success) {
                    alert(data.message || 'Status alat diperbarui');
                    window.location.reload();
                } else {
                    alert((data && data.message) ? data.message : 'Terjadi kesalahan');
                }
            }).catch(function(err){
                alert('Request gagal: ' + err);
            });
        });
    });
});
</script>

<?= $this->endSection() ?>
