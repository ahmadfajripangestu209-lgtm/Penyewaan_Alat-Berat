<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>
<?php $profil = require(APPPATH . 'Config/profil_perusahaan.php'); $foto = $profil['foto'] ?? 'company-profile.jpg'; ?>
<div class="container my-5">
    <div class="row align-items-center mb-5">
        <div class="col-md-4 text-center mb-4 mb-md-0">
            <img src="<?= base_url('images/' . $foto) ?>" alt="Edi Jaya Jambi" class="img-fluid rounded shadow" style="max-height:220px;object-fit:cover;">
        </div>
        <div class="col-md-8">
            <h1 class="mb-2" style="font-weight:700;letter-spacing:1px;">Tentang Kami</h1>
            <h4 class="text-warning mb-3">Edi Jaya Jambi</h4>
            <p class="lead mb-3">Penyewaan alat berat terpercaya di Jambi untuk kebutuhan proyek konstruksi, pertambangan, dan infrastruktur. Berpengalaman, profesional, dan siap melayani Anda dengan armada terbaik.</p>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm mb-4 border-0" style="border-radius:18px;">
                <div class="card-body">
                    <h5 class="mb-4 text-center text-primary" style="font-weight:600;">Keunggulan Kami</h5>
                    <div class="row text-center g-4">
                        <div class="col-6 col-md-4">
                            <i class="fas fa-truck-monster fa-2x text-warning mb-2"></i>
                            <div class="fw-bold">Armada Lengkap</div>
                            <div class="small text-muted">Alat berat terawat & siap kerja</div>
                        </div>
                        <div class="col-6 col-md-4">
                            <i class="fas fa-user-tie fa-2x text-warning mb-2"></i>
                            <div class="fw-bold">Operator Profesional</div>
                            <div class="small text-muted">Berpengalaman & bersertifikat</div>
                        </div>
                        <div class="col-6 col-md-4">
                            <i class="fas fa-headset fa-2x text-warning mb-2"></i>
                            <div class="fw-bold">Layanan Responsif</div>
                            <div class="small text-muted">Konsultasi & support 24/7</div>
                        </div>
                        <div class="col-6 col-md-4">
                            <i class="fas fa-tags fa-2x text-warning mb-2"></i>
                            <div class="fw-bold">Harga Kompetitif</div>
                            <div class="small text-muted">Sesuai kebutuhan proyek Anda</div>
                        </div>
                        <div class="col-6 col-md-4">
                            <i class="fas fa-building fa-2x text-warning mb-2"></i>
                            <div class="fw-bold">Proyek Skala Besar & Kecil</div>
                            <div class="small text-muted">Siap melayani semua kebutuhan</div>
                        </div>
                        <div class="col-6 col-md-4">
                            <i class="fas fa-thumbs-up fa-2x text-warning mb-2"></i>
                            <div class="fw-bold">Kepuasan Pelanggan</div>
                            <div class="small text-muted">Prioritas utama kami</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-center mt-4">
                <a href="<?= base_url('kontak') ?>" class="btn btn-warning btn-lg px-5 fw-bold">Hubungi Kami</a>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?> 