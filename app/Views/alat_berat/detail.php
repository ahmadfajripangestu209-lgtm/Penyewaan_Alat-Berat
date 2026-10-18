<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="container my-5">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url('alat-berat') ?>">Alat Berat</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?= $alat['nama'] ?></li>
        </ol>
    </nav>

    <div class="row">
        <!-- Image Section with Carousel -->
        <div class="col-md-6 mb-4">
            <div class="card border-0 shadow-sm">
                <?php
                // Ambil galeri gambar dari controller
                $images = [];
                if (!empty($galeri)) {
                    foreach ($galeri as $g) {
                        $images[] = base_url('public/uploads/' . $g['filename']);
                    }
                }
                // Jika tidak ada galeri, pakai gambar utama
                if (empty($images)) {
                    if (!empty($alat['gambar'])) {
                        $images[] = base_url('images/alat_berat/' . $alat['gambar']);
                    } else {
                        $images[] = base_url('images/default-equipment.jpg');
                    }
                }
                ?>
                <?php if (count($images) > 1): ?>
                    <div id="alatCarousel" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            <?php foreach ($images as $idx => $img): ?>
                                <div class="carousel-item<?= $idx === 0 ? ' active' : '' ?>">
                                    <img src="<?= $img ?>" class="d-block w-100" alt="<?= $alat['nama'] ?>"
                                        style="height: 400px; object-fit: cover;">
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#alatCarousel"
                            data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#alatCarousel"
                            data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>
                <?php else: ?>
                    <img src="<?= $images[0] ?>" class="card-img-top" alt="<?= $alat['nama'] ?>"
                        style="height: 400px; object-fit: cover;">
                <?php endif; ?>
            </div>
            <!-- Spesifikasi Alat Berat -->
            <div class="card border-0 shadow-sm mt-3">
                <div class="card-body">
                    <h5 class="card-title mb-3"><i class="fas fa-cogs me-2 text-warning"></i>Spesifikasi</h5>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover align-middle mb-0 border rounded"
                            style="overflow:hidden;">
                            <tbody>
                                <tr>
                                    <th class="bg-light text-muted" style="width:60%"><i
                                            class="fas fa-weight-hanging me-1 text-secondary"></i> Berat Operasional
                                    </th>
                                    <td class="fw-semibold text-dark">
                                        <?= !empty($alat['berat_operasional']) ? $alat['berat_operasional'] . ' kg' : '-' ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="bg-light text-muted"><i class="fas fa-box-open me-1 text-secondary"></i>
                                        Kapasitas Bucket</th>
                                    <td class="fw-semibold text-dark">
                                        <?= !empty($alat['kapasitas_bucket']) ? $alat['kapasitas_bucket'] . ' m³' : '-' ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="bg-light text-muted"><i
                                            class="fas fa-arrow-down me-1 text-secondary"></i> Kedalaman Gali Maksimum
                                    </th>
                                    <td class="fw-semibold text-dark">
                                        <?= !empty($alat['kedalaman_gali_maksimum']) ? $alat['kedalaman_gali_maksimum'] . ' m' : '-' ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="bg-light text-muted"><i
                                            class="fas fa-arrows-alt-v me-1 text-secondary"></i> Tinggi (Transportasi)
                                    </th>
                                    <td class="fw-semibold text-dark">
                                        <?= !empty($alat['tinggi_transportasi']) ? $alat['tinggi_transportasi'] . ' m' : '-' ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="bg-light text-muted"><i
                                            class="fas fa-arrows-alt-h me-1 text-secondary"></i> Panjang (Transportasi)
                                    </th>
                                    <td class="fw-semibold text-dark">
                                        <?= !empty($alat['panjang_transportasi']) ? $alat['panjang_transportasi'] . ' m' : '-' ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="bg-light text-muted"><i
                                            class="fas fa-expand-arrows-alt me-1 text-secondary"></i> Lebar
                                        (Transportasi)</th>
                                    <td class="fw-semibold text-dark">
                                        <?= !empty($alat['lebar_transportasi']) ? $alat['lebar_transportasi'] . ' m' : '-' ?>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Details Section -->
        <div class="col-md-6">
            <h1 class="h2 mb-4"><?= $alat['nama'] ?></h1>

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="card-title mb-3">Informasi Alat</h5>
                    <div class="row mb-3">
                        <div class="col-md-4 text-muted">Jenis</div>
                        <div class="col-md-8"><?= $alat['jenis'] ?></div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4 text-muted">Merek</div>
                        <div class="col-md-8"><?= $alat['merek'] ?? '-' ?></div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4 text-muted">Tipe</div>
                        <div class="col-md-8"><?= $alat['tipe'] ?? '-' ?></div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4 text-muted">Kapasitas</div>
                        <div class="col-md-8"><?= $alat['kapasitas'] ?> ton</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4 text-muted">Status</div>
                        <div class="col-md-8">
                            <span
                                class="badge bg-<?= $alat['status'] === 'tersedia' ? 'success' : ($alat['status'] === 'dipinjam' ? 'warning' : 'danger') ?>">
                                <?= ucfirst($alat['status']) ?>
                            </span>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4 text-muted">Harga Sewa</div>
                        <div class="col-md-8">
                            <span
                                class="text-warning fw-bold"><?= !empty($alat['harga_sewa']) ? (is_numeric($alat['harga_sewa']) ? 'Rp ' . number_format((float) $alat['harga_sewa'], 0, ',', '.') : $alat['harga_sewa']) : 'Rp 0' ?></span>
                            / jam
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4 text-muted">Harga Operator</div>
                        <div class="col-md-8">
                            <span
                                class="text-warning fw-bold"><?= !empty($alat['operator']) ? (is_numeric($alat['operator']) ? 'Rp ' . number_format((float) $alat['operator'], 0, ',', '.') : $alat['operator']) : 'Rp 0' ?></span>
                            / jam
                        </div>
                    </div>
                </div>
            </div>

            <?php if (!empty($alat['deskripsi'])): ?>
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Deskripsi</h5>
                        <p class="card-text"><?= nl2br($alat['deskripsi']) ?></p>
                    </div>
                </div>
            <?php endif; ?>

            <?php if ($alat['status'] === 'tersedia'): ?>
                <div class="d-grid gap-2 mb-3">
                    <a href="<?= base_url('alat-berat/minta-penawaran-form/' . $alat['id']) ?>"
                        class="btn btn-warning btn-lg w-100 mb-2">
                        <i class="fas fa-file-invoice me-2"></i> Ajukan Penawaran
                    </a>
                    <a href="<?= base_url('pembayaran/alat/' . $alat['id']) ?>" class="btn btn-success btn-lg w-100">
                        <i class="fas fa-money-bill-wave me-2"></i> Bayar
                    </a>
                </div>
            <?php else: ?>
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Alat berat ini sedang tidak tersedia untuk disewa
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
