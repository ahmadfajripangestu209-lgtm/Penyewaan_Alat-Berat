<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm mb-4">
                <div class="row g-0">
                    <?php if (!empty($alat['foto'])): ?>
                    <div class="col-md-5 d-flex align-items-center justify-content-center p-3">
                        <img src="<?= base_url('images/' . $alat['foto']) ?>" alt="<?= esc($alat['nama']) ?>" class="img-fluid" style="max-height:220px;object-fit:contain;">
                    </div>
                    <?php endif; ?>
                    <div class="col-md-7">
                        <div class="card-body">
                            <h2 class="card-title mb-2"><?= esc($alat['nama']) ?></h2>
                            <p class="mb-1"><strong>Jenis:</strong> <?= esc($alat['jenis']) ?></p>
                            <p class="mb-1"><strong>Tipe:</strong> <?= esc($alat['tipe']) ?></p>
                            <p class="mb-1"><strong>Merek:</strong> <?= esc($alat['merek'] ?? '-') ?></p>
                            <p class="mb-1"><strong>Kapasitas:</strong> <?= esc($alat['kapasitas']) ?> ton</p>
                            <p class="mb-1"><strong>Status:</strong> <?= esc($alat['status']) ?></p>
                            <p class="mb-1"><strong>Harga Sewa Per Jam:</strong> <span class="fw-bold text-success"><?= !empty($alat['harga']) ? (is_numeric($alat['harga']) ? 'Rp ' . number_format((float)$alat['harga'], 0, ',', '.') : $alat['harga']) : 'Rp 0' ?></span></p>
                            <p class="mb-1"><strong>Minimum Sewa:</strong> <?= esc($alat['minimum_sewa']) ?></p>
                            <p class="mb-1"><strong>Harian Operator:</strong> <?= !empty($alat['operator']) ? (is_numeric($alat['operator']) ? 'Rp ' . number_format((float)$alat['operator'], 0, ',', '.') : $alat['operator']) : 'Rp 0' ?></p>
                            <div class="mt-4">
                                <a href="<?= base_url('alat-berat') ?>" class="btn btn-secondary">Kembali</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?> 