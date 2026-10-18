<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>
<div class="container my-5 text-center">
    <div class="alert alert-success p-4">
        <h2 class="mb-3"><i class="fas fa-check-circle text-success me-2"></i> Pembayaran berhasil dikirim</h2>
        <p class="lead">Pembayaran Anda telah kami terima dan menunggu verifikasi admin.</p>
        <a href="<?= base_url('alat-berat/riwayat-penawaran') ?>" class="btn btn-warning">Kembali ke Riwayat</a>
    </div>
</div>
<?= $this->endSection() ?>
