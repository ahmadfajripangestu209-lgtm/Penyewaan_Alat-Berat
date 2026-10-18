<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<style>
.alat-card {
    opacity: 0;
    transform: translateY(40px);
    animation: fadeUp 0.7s forwards;
    border: 2px solid #222 !important;
    border-radius: 18px !important;
    background: #fff;
}
.alat-card:nth-child(1) { animation-delay: 0.1s; }
.alat-card:nth-child(2) { animation-delay: 0.2s; }
.alat-card:nth-child(3) { animation-delay: 0.3s; }
.alat-card:nth-child(4) { animation-delay: 0.4s; }
.alat-card:nth-child(5) { animation-delay: 0.5s; }
.alat-card:nth-child(6) { animation-delay: 0.6s; }
@keyframes fadeUp {
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
.section-title {
    opacity: 0;
    transform: translateY(30px);
    animation: fadeInTitle 0.7s 0.2s forwards;
}
@keyframes fadeInTitle {
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
.filter-sidebar {
    min-width: 220px;
    max-width: 300px;
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    padding: 20px;
}
.filter-sidebar .form-check {
    margin-bottom: 8px;
}
.filter-sidebar .fw-bold {
    font-size: 1.05rem;
}
</style>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<div class="container my-5">
  <div class="row">
    <div class="col-md-3 mb-4">
      <div class="card filter-sidebar">
        <div class="card-body">
          <h5 class="mb-3"><i class="fas fa-filter me-2"></i>FILTER</h5>
          <form method="get" action="">
            <!-- Filter Jenis -->
            <?php
            // Gabungkan jenis alat berat yang mirip
            $jenisMap = [];
            foreach ($jenisList as $jenis) {
                $key = strtolower(str_replace([' ', '-'], '', $jenis));
                if (!isset($jenisMap[$key])) {
                    $jenisMap[$key] = $jenis;
                }
            }
            ?>
            <div class="mb-3">
              <div class="fw-bold mb-2">Jenis Alat Berat</div>
              <?php foreach ($jenisMap as $key => $jenis): ?>
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" name="jenis[]" value="<?= $jenis ?>" id="jenis-<?= strtolower(str_replace(' ', '-', $jenis)) ?>"
                    <?= (isset($filter_jenis) && is_array($filter_jenis) && in_array($jenis, $filter_jenis)) ? 'checked' : '' ?>>
                  <label class="form-check-label" for="jenis-<?= strtolower(str_replace(' ', '-', $jenis)) ?>">
                    <?= ucwords(str_replace('-', ' ', $jenis)) ?>
                  </label>
                </div>
              <?php endforeach; ?>
            </div>
            <!-- Filter Merek -->
            <div class="mb-3">
              <div class="fw-bold mb-2">Merek</div>
              <?php foreach ($merekList as $merek): ?>
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" name="merek[]" value="<?= $merek ?>" id="merek-<?= strtolower(str_replace(' ', '-', $merek)) ?>"
                    <?= (isset($filter_merek) && is_array($filter_merek) && in_array($merek, $filter_merek)) ? 'checked' : '' ?>>
                  <label class="form-check-label" for="merek-<?= strtolower(str_replace(' ', '-', $merek)) ?>">
                    <?= $merek ?>
                  </label>
                </div>
              <?php endforeach; ?>
            </div>
            <!-- Filter Kapasitas (Ton) -->
            <div class="mb-3">
              <div class="fw-bold mb-2">Kapasitas (Ton)</div>
              <?php $kapasitasList = [
                '5-10' => '5 &gt;&gt; 10 Ton',
                '10-15' => '10 &gt;&gt; 15 Ton',
                '15-20' => '15 &gt;&gt; 20 Ton',
                '20-25' => '20 &gt;&gt; 25 Ton',
                '25-30' => '25 &gt;&gt; 30 Ton',
              ]; ?>
              <?php foreach ($kapasitasList as $key => $label): ?>
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" name="kapasitas[]" value="<?= $key ?>" id="kapasitas-<?= $key ?>" <?= (isset($filter_kapasitas) && is_array($filter_kapasitas) && in_array($key, $filter_kapasitas)) ? 'checked' : '' ?>>
                  <label class="form-check-label" for="kapasitas-<?= $key ?>">
                    <?= $label ?>
                  </label>
                </div>
              <?php endforeach; ?>
            </div>
            <div class="mb-3">
              <div class="fw-bold mb-2">Status</div>
              <?php $statusList = ['tersedia' => 'Tersedia', 'dipinjam' => 'Dipinjam', 'rusak' => 'Rusak']; ?>
              <?php foreach ($statusList as $val => $label): ?>
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" name="status[]" value="<?= $val ?>" id="status-<?= $val ?>" <?= (isset($filter_status) && is_array($filter_status) && in_array($val, $filter_status)) ? 'checked' : '' ?>>
                  <label class="form-check-label" for="status-<?= $val ?>">
                    <?= $label ?>
                  </label>
                </div>
              <?php endforeach; ?>
            </div>
            <button type="submit" class="btn btn-primary w-100 mt-3">Terapkan</button>
          </form>
        </div>
        </div>
        </div>
    <div class="col-md-9">
      <!-- Daftar alat berat -->
    <?php $q = service('request')->getGet('q'); ?>
    <?php if ($q): ?>
        <div class="alert alert-info mb-4">
            Hasil pencarian untuk <strong>"<?= esc($q) ?>"</strong> &mdash; <strong><?= count($alat_berat) ?></strong> ditemukan.
        </div>
    <?php endif; ?>
    <?php if (empty($alat_berat)): ?>
        <div class="alert alert-warning text-center">Tidak ada alat berat yang ditemukan<?= $q ? ' untuk kata kunci <strong>"' . esc($q) . '"</strong>' : '' ?>.</div>
    <?php else: ?>
    <div class="row g-4">
        <?php $i=1; foreach ($alat_berat as $alat): ?>
        <div class="col-md-4">
            <div class="card h-100 shadow-sm border-0 alat-card" style="border-radius:14px;transition:transform 0.2s;animation-delay:<?= ($i*0.1) ?>s;">
                <div class="position-relative">
                    <?php
                    $img = 'images/default-equipment.jpg';
                    if (!empty($alat['gambar'])) {
                        $imgPath = FCPATH . 'images/alat_berat/' . $alat['gambar'];
                        if (is_file($imgPath)) {
                            $img = 'images/alat_berat/' . $alat['gambar'];
                        } else {
                            $imgPath2 = FCPATH . 'images/' . $alat['gambar'];
                            if (is_file($imgPath2)) {
                                $img = 'images/' . $alat['gambar'];
                            }
                        }
                    }
                    ?>
                    <img src="<?= base_url($img) ?>" 
                         class="card-img-top" 
                         alt="<?= $alat['nama'] ?>" 
                         style="height: 200px; object-fit: cover; border-radius:14px 14px 0 0;">
                    <span class="position-absolute top-0 end-0 m-3 badge bg-<?= $alat['status'] === 'tersedia' ? 'success' : ($alat['status'] === 'dipinjam' ? 'warning' : 'danger') ?>">
                        <?= ucfirst($alat['status']) ?>
                    </span>
                </div>
                <div class="card-body">
                    <h5 class="card-title mb-2" style="font-weight:600;"><?= $alat['nama'] ?></h5>
                    <div class="mb-2 text-muted small">Merek: <?= $alat['merek'] ?? '-' ?></div>
                    <div class="mb-2 text-muted small">Kapasitas: <?= $alat['kapasitas'] ?? '-' ?> Ton</div>
                    <a href="<?= base_url('alat-berat/detail/' . $alat['id']) ?>" class="btn btn-warning w-100 fw-bold">LIHAT DETAIL</a>
                </div>
            </div>
        </div>
        <?php $i++; endforeach; ?>
    </div>
    <?php endif; ?>
</div>
  </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    if (window.jQuery) {
        // The select2 initialization for jenis is removed as per the new_code.
        // If specific functionality for jenis select is needed, it should be re-added.
    }
});
</script>
<?= $this->endSection() ?> 