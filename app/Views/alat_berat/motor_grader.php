<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>
<div class="container my-5">
    <h1 class="mb-2" style="font-weight:600;">Motor Grader</h1>
    <div class="mb-3" style="color:#666;max-width:700px;">
        <?= $deskripsi ?>
    </div>
    <?php foreach($list as $item): ?>
    <div class="card mb-4 shadow-sm border-0" style="border-radius:14px;">
        <div class="row g-0 align-items-center">
            <div class="col-md-3 text-center">
                <?php
                $img = 'images/default-equipment.jpg';
                if (!empty($item['gambar'])) {
                    $imgPath = FCPATH . 'images/alat_berat/' . $item['gambar'];
                    if (is_file($imgPath)) {
                        $img = 'images/alat_berat/' . $item['gambar'];
                    } else {
                        $imgPath2 = FCPATH . 'images/' . $item['gambar'];
                        if (is_file($imgPath2)) {
                            $img = 'images/' . $item['gambar'];
                        }
                    }
                }
                ?>
                <img src="<?= base_url($img) ?>" alt="<?= $item['nama'] ?>" style="max-height:240px;max-width:300px;width:100%;object-fit:contain;padding:0.5rem 0;" class="mx-auto d-block">
            </div>
            <div class="col-md-9">
                <div class="card-body">
                    <h5 class="card-title mb-1" style="font-weight:600;"><?= $item['nama'] ?></h5>
                    <div class="row mb-3 align-items-center">
                        <div class="col-4 small">Merek<br><span class="fw-semibold text-dark" style="font-size:1.1rem;"><?= !empty($item['merek']) ? $item['merek'] : '-' ?></span></div>
                        <div class="col-4 text-center small">Kapasitas<br><span class="fw-bold" style="font-size:1.1rem;"><?= $item['kapasitas'] ?? '-' ?> ton</span></div>
                        <div class="col-4 text-end small">Harga Sewa<br><span class="text-warning fw-bold" style="font-size:1.1rem;"><?= !empty($item['harga_sewa']) ? (is_numeric($item['harga_sewa']) ? 'Rp ' . number_format((float)$item['harga_sewa'], 0, ',', '.') : $item['harga_sewa']) : '-' ?>/jam</span></div>
                    </div>
                    <a href="<?= base_url('alat-berat/detail/' . $item['id']) ?>" class="btn btn-warning w-100 fw-bold py-2" style="font-size:1.1rem;">LIHAT DETAIL</a>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?= $this->endSection() ?> 