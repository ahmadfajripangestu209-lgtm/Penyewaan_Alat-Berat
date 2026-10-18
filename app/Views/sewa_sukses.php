<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>
<div class="container my-5 text-center">
    <div class="alert alert-success p-4">
        <h2 class="mb-3"><i class="fas fa-check-circle text-success me-2"></i> Permintaan Penawaran Berhasil Dikirim!</h2>
        <p class="lead">
Permintaan penawaran untuk <b><?= esc($alat) ?></b> sudah kami terima.</p>
        <p class="mb-4">Tim kami akan segera menghubungi Anda melalui kontak yang Anda berikan.</p>
        <a href="<?= base_url('/') ?>" class="btn btn-warning">Kembali ke Beranda</a>
    </div>
</div>
<?= $this->endSection() ?> 