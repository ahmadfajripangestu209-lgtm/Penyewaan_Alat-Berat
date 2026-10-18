<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>
<div class="container my-5">
    <h1 class="mb-2" style="font-weight:600;"><?= $title ?></h1>
    <div class="mb-3" style="color:#666;max-width:700px;">
        <?= $deskripsi ?>
    </div>
    <?php if (empty($alat_berat)): ?>
        <div class="alert alert-warning">Belum ada data alat berat untuk jenis ini.</div>
    <?php endif; ?>
    <?php foreach($alat_berat as $item): ?>
    <div class="card mb-4 shadow-sm border-0" style="border-radius:14px;">
        <div class="row g-0 align-items-center">
            <div class="col-md-3">
                <div class="position-relative" style="height:200px;overflow:hidden;border-radius:14px 0 0 14px;">
                    <img src="<?php
                        $uploadPath = 'uploads/' . $item['gambar'];
                        $imagePath = 'images/' . $item['gambar'];
                        if (isset($item['gambar']) && $item['gambar']) {
                            if (file_exists(FCPATH . $uploadPath)) {
                                echo base_url($uploadPath);
                            } elseif (file_exists(FCPATH . $imagePath)) {
                                echo base_url($imagePath);
                            } else {
                                echo base_url('images/default-equipment.jpg');
                            }
                        } else {
                            echo base_url('images/default-equipment.jpg');
                        }
                    ?>"
                         alt="<?= $item['nama'] ?>" 
                         style="width:100%;height:100%;object-fit:cover;filter:brightness(0.9);">
                    <div class="position-absolute top-0 start-0 w-100 h-100" style="background:linear-gradient(45deg,rgba(0,0,0,0.4),transparent);"></div>
                    <span class="position-absolute top-0 end-0 m-3 badge bg-<?= $item['status'] === 'tersedia' ? 'success' : ($item['status'] === 'dipinjam' ? 'warning' : 'danger') ?>">
                        <?= ucfirst($item['status']) ?>
                    </span>
                </div>
            </div>
            <div class="col-md-9">
                <div class="card-body">
                    <h5 class="card-title mb-1" style="font-weight:600;"><?= $item['nama'] ?></h5>
                    <div class="row mb-3">
                        <div class="col-6 col-md-4 small">Kapasitas<br><span class="fw-bold" style="font-size:1.1rem;"><?= $item['kapasitas'] ?> ton</span></div>
                        <div class="col-6 col-md-4 small">Harga Sewa<br><span class="text-warning fw-bold" style="font-size:1.1rem;"><?= !empty($item['harga_sewa']) ? (is_numeric($item['harga_sewa']) ? 'Rp ' . number_format((float)$item['harga_sewa'], 0, ',', '.') : $item['harga_sewa']) : '-' ?>/hari</span></div>
                    </div>
                    <a href="<?= base_url('alat-berat/detail/' . $item['id']) ?>" class="btn btn-warning w-100 fw-bold py-2" style="font-size:1.1rem;">LIHAT DETAIL</a>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?= $this->endSection() ?> 