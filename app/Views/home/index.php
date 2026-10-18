<?= $this->extend('layout/main') ?>
<style>
body {
  font-size: 1.12rem;
}
.display-4, .h1, h1 {
  font-size: 3.1rem;
}
.card-body, .lead, .h2, h2 {
  font-size: 1.28rem;
}
.btn, .btn-lg {
  font-size: 1.18rem;
  padding: 0.85rem 2.3rem;
}
@media (max-width: 991px) {
  .display-4, .h1, h1 { font-size: 2.1rem; }
  .card-body, .lead, .h2, h2 { font-size: 1.08rem; }
}
</style>
<?php $profil = require(APPPATH . 'Config/profil_perusahaan.php'); $foto_header = $profil['foto_header'] ?? 'default-header.jpg'; ?>
<?= $this->section('content') ?>
<?php if (session('message')): ?>
<div class="container mt-3" style="max-width:1000px;">
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i><?= session('message') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
</div>
<?php endif; ?>
<?php if (session('error')): ?>
<div class="container mt-3" style="max-width:1000px;">
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-triangle me-2"></i><?= session('error') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
</div>
<?php endif; ?>
<!-- Hero Section -->
<div class="bg-dark text-white py-6" style="padding-top:5rem;padding-bottom:9rem;">
    <div class="container-fluid px-5" style="max-width:1400px;margin:auto;">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="display-4 fw-bold text-warning"><?= htmlspecialchars($profil['hero_title'] ?? 'Edi Jaya Jambi') ?></h1>
                <h2 class="h3 mb-4"><?= htmlspecialchars($profil['hero_subtitle'] ?? 'Penyewaan Alat Berat Terpercaya') ?></h2>
                <p class="lead mb-4"><?= htmlspecialchars($profil['hero_desc'] ?? 'Solusi terbaik untuk kebutuhan alat berat Anda. Kami menyediakan berbagai jenis alat berat dengan kualitas terjamin dan harga kompetitif.') ?></p>
                <a href="<?= base_url('alat-berat') ?>" class="btn btn-warning btn-lg">Lihat Alat Berat</a>
            </div>
            <div class="col-md-6">
                <img src="<?= base_url('images/' . $foto_header) ?>" alt="Alat Berat" class="img-fluid rounded shadow">
            </div>
        </div>
    </div>
</div>
<!-- Search Box -->
<div class="container-fluid" style="margin-top:-80px;z-index:2;position:relative;max-width:900px;margin-left:auto;margin-right:auto;">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <form action="<?= base_url('alat-berat') ?>" method="get" class="shadow rounded p-3 bg-white d-flex align-items-center" style="gap:12px;">
                <input type="text" name="q" class="form-control" placeholder="Cari alat berat..." style="border-radius:8px; padding-top:0.7rem; padding-bottom:0.7rem;">
                <button type="submit" class="btn btn-warning px-4" style="border-radius:8px; padding-top:0.6rem; padding-bottom:0.6rem;">Cari</button>
            </form>
        </div>
    </div>
</div>

<!-- Pilihan Rental Beragam -->
<style>
.rental-anim {
    opacity: 0;
    transform: translateY(40px);
    animation: fadeUpRental 1s forwards;
}
.rental-anim-1 { animation-delay: 0.2s; }
.rental-anim-2 { animation-delay: 0.4s; }
.rental-anim-3 { animation-delay: 0.6s; }
.rental-anim-4 { animation-delay: 0.8s; }
.rental-anim-5 { animation-delay: 1.0s; }
.rental-anim-6 { animation-delay: 1.2s; }
.rental-anim-7 { animation-delay: 1.4s; }
@keyframes fadeUpRental {
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
.rental-pop {
    transition: transform 0.18s cubic-bezier(.4,2,.6,1), box-shadow 0.18s;
}
.rental-pop:active {
    transform: scale(1.08);
    box-shadow: 0 8px 32px rgba(0,0,0,0.13);
}
</style>
<div class="container-fluid my-5" style="max-width:1400px;margin:auto;">
    <h2 class="mb-4 text-center" style="font-weight:600;">Pilihan rental beragam</h2>
    <div class="row g-4 mb-2 justify-content-center">
        <div class="col-6 col-md-3 rental-anim rental-anim-1">
            <a href="<?= base_url('alat-berat/excavator') ?>" class="text-decoration-none text-dark">
                <div class="card text-center shadow-sm h-100 rental-pop">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
                        <img src="<?= base_url('images/excavator.png') ?>" alt="Bucket Excavator" style="height:120px;object-fit:contain;">
                        <div class="fw-bold mt-3">Bucket<br>Excavator</div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-6 col-md-3 rental-anim rental-anim-2">
            <a href="<?= base_url('alat-berat/vibro-roller') ?>" class="text-decoration-none text-dark">
                <div class="card text-center shadow-sm h-100 rental-pop">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
                        <img src="<?= base_url('images/vibro.png') ?>" alt="Vibro Roller" style="height:120px;object-fit:contain;">
                        <div class="fw-bold mt-3">Vibro<br>Roller</div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-6 col-md-3 rental-anim rental-anim-3">
            <a href="<?= base_url('alat-berat/dozer') ?>" class="text-decoration-none text-dark">
                <div class="card text-center shadow-sm h-100 rental-pop">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
                        <img src="<?= base_url('images/dozer.png') ?>" alt="Dozer" style="height:120px;object-fit:contain;">
                        <div class="fw-bold mt-3">Dozer</div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-6 col-md-3 rental-anim rental-anim-4">
            <a href="<?= base_url('alat-berat/motor-grader') ?>" class="text-decoration-none text-dark">
                <div class="card text-center shadow-sm h-100 rental-pop">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
                        <img src="<?= base_url('images/motor-grader.png') ?>" alt="Motor Grader" style="height:120px;object-fit:contain;">
                        <div class="fw-bold mt-3">Motor Grader</div>
                    </div>
                </div>
            </a>
        </div>
    </div>
    <div class="row g-4 justify-content-center">
        <div class="col-6 col-md-4 rental-anim rental-anim-5">
            <a href="<?= base_url('alat-berat/wheel-loader') ?>" class="text-decoration-none text-dark">
                <div class="card text-center shadow-sm h-100 rental-pop">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
                        <img src="<?= base_url('images/wheel-loader.png') ?>" alt="Wheel Loader" style="height:120px;object-fit:contain;">
                        <div class="fw-bold mt-3">Wheel Loader</div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-6 col-md-4 rental-anim rental-anim-6">
            <a href="<?= base_url('alat-berat/backhoe-loader') ?>" class="text-decoration-none text-dark">
                <div class="card text-center shadow-sm h-100 rental-pop">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
                        <img src="<?= base_url('images/backhoe-loader.png') ?>" alt="Backhoe Loader" style="height:120px;object-fit:contain;">
                        <div class="fw-bold mt-3">Backhoe loader</div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-6 col-md-4 rental-anim rental-anim-7">
            <a href="<?= base_url('alat-berat/dump-truck') ?>" class="text-decoration-none text-dark">
                <div class="card text-center shadow-sm h-100 rental-pop">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
                        <img src="<?= base_url('images/dump-trucks.png') ?>" alt="Dump Truck" style="height:120px;object-fit:contain;">
                        <div class="fw-bold mt-3">Dump Truck</div>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>

<!-- Keunggulan Section -->
<div class="container-fluid my-5" style="max-width:1400px;margin:auto;">
    <h2 class="text-center mb-5">Mengapa Memilih Kami?</h2>
    <div class="row g-4">
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body text-center">
                    <i class="fas fa-truck-monster fa-3x text-warning mb-3"></i>
                    <h3 class="h5">Alat Berat Berkualitas</h3>
                    <p class="text-muted">Kami menyediakan alat berat dengan kondisi terbaik dan terawat.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body text-center">
                    <i class="fas fa-clock fa-3x text-warning mb-3"></i>
                    <h3 class="h5">Layanan 24/7</h3>
                    <p class="text-muted">Siap melayani Anda kapan saja dengan tim support yang responsif.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body text-center">
                    <i class="fas fa-hand-holding-usd fa-3x text-warning mb-3"></i>
                    <h3 class="h5">Harga Bersaing</h3>
                    <p class="text-muted">Dapatkan harga terbaik dengan kualitas yang tidak diragukan.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Kontak Section -->
<div class="container-fluid my-5" style="max-width:1000px;margin:auto;">
    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="card border-0 shadow-sm p-4" style="border-radius:18px;">
                <h2 class="mb-4 text-center">Hubungi Kami</h2>
                <p class="mb-4 text-center">Untuk informasi lebih lanjut tentang layanan kami, silakan hubungi:</p>
                <ul class="list-unstyled text-center mb-0">
                    <li class="mb-3">
                        <i class="fas fa-phone me-2 text-warning"></i>
                        +62 887-0707-7027
                    </li>
                    <li class="mb-3">
                        <i class="fas fa-envelope me-2 text-warning"></i>
                        info@edijayajambi.com
                    </li>
                    <li class="mb-3">
                        <i class="fas fa-map-marker-alt me-2 text-warning"></i>
                        Tanjung Mulya, Kec. Bahar Sel., Kabupaten Muaro Jambi, Jambi 30756
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?> 
