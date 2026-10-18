<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h2 class="card-title fw-bold text-primary mb-0">
                            <i class="fas fa-bell me-2"></i>Notifikasi
                        </h2>
                        <?php if (!empty($notifikasi)): ?>
                            <form action="<?= base_url('notifikasi/hapus-semua') ?>" method="post" onsubmit="return confirm('Hapus semua notifikasi?');">
                                <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i> Hapus Semua</button>
                            </form>
                        <?php endif; ?>
                    </div>
                    <?php if (empty($notifikasi)): ?>
                        <div class="alert alert-info">Belum ada notifikasi.</div>
                    <?php else: ?>
                        <ul class="list-group list-group-flush">
                            <?php foreach ($notifikasi as $n): ?>
                                <li class="list-group-item d-flex justify-content-between align-items-start <?= $n['is_read'] ? '' : 'bg-light' ?>">
                                    <div class="w-100 me-3">
                                        <a href="<?= base_url('notifikasi/baca/' . $n['id']) ?>" class="d-flex justify-content-between align-items-start text-decoration-none text-dark">
                                            <div class="ms-2 me-auto">
                                                <div class="fw-bold">
                                                    <?php
                                                        $finishedBadge = strpos($n['pesan'], 'telah berakhir') !== false || strpos($n['pesan'], 'telah selesai') !== false;
                                                        if ($n['tipe'] === 'kuotasi') {
                                                            echo "<i class='fas fa-file-invoice text-warning me-1'></i>";
                                                        } elseif ($finishedBadge) {
                                                            echo "<i class='fas fa-exclamation-circle text-danger me-1'></i>";
                                                        } else {
                                                            echo "<i class='fas fa-envelope text-primary me-1'></i>";
                                                        }
                                                    ?>
                                                    <?= $n['pesan'] ?>
                                                    <?php if ($finishedBadge): ?>
                                                        <span class="badge bg-danger ms-2">Sewa Berakhir</span>
                                                    <?php endif; ?>
                                                </div>
                                                <small class="text-muted"><?php
                                                    $dt = new DateTime($n['created_at'], new DateTimeZone('UTC'));
                                                    $dt->setTimezone(new DateTimeZone('Asia/Jakarta'));
                                                    echo $dt->format('d-m-Y H:i');
                                                ?> WIB</small>
                                            </div>
                                            <?php if (!$n['is_read']): ?>
                                                <span class="badge bg-primary rounded-pill">Baru</span>
                                            <?php endif; ?>
                                        </a>
                                    </div>
                                    <div class="d-flex flex-column align-items-end gap-1">
                                        <form action="<?= base_url('notifikasi/hapus/' . $n['id']) ?>" method="post" onsubmit="return confirm('Hapus notifikasi ini?');">
                                            <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                                        </form>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?> 