<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h2 class="mb-3">Pengaturan Profil Perusahaan</h2>
                    <?php if (session('message')): ?>
                        <div class="alert alert-success"> <?= session('message') ?> </div>
                    <?php endif; ?>
                    <?php if (session('error')): ?>
                        <div class="alert alert-danger"> <?= session('error') ?> </div>
                    <?php endif; ?>
                    <div class="mb-4 text-center">
                        <img src="<?= base_url('images/' . $foto) ?>" alt="Foto Profil Perusahaan" class="img-fluid rounded shadow" style="max-height:180px;object-fit:cover;">
                    </div>
                    <form action="<?= base_url('admin/pengaturan/upload-foto') ?>" method="post" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="foto" class="form-label">Upload Foto Profil Baru</label>
                            <input type="file" class="form-control" id="foto" name="foto" accept="image/*" required>
                        </div>
                        <button type="submit" class="btn btn-warning">Upload & Simpan</button>
                    </form>
                    <hr class="my-4">
                    <div class="mb-4 text-center">
                        <img src="<?= base_url('images/' . $foto_header) ?>" alt="Header Homepage" class="img-fluid rounded shadow" style="max-height:180px;object-fit:cover;">
                        <div class="small text-muted mt-2">Gambar Header Homepage Saat Ini</div>
                    </div>
                    <form action="<?= base_url('admin/pengaturan/upload-foto-header') ?>" method="post" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="foto_header" class="form-label">Upload Gambar Header Homepage</label>
                            <input type="file" class="form-control" id="foto_header" name="foto_header" accept="image/*" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Upload & Simpan Header</button>
                    </form>
                    <form action="<?= base_url('admin/pengaturan/update-hero') ?>" method="post">
                        <div class="mb-3">
                            <label for="hero_title" class="form-label">Judul Utama Homepage</label>
                            <input type="text" class="form-control" id="hero_title" name="hero_title" value="<?= htmlspecialchars($hero_title ?? '') ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="hero_subtitle" class="form-label">Subjudul Homepage</label>
                            <input type="text" class="form-control" id="hero_subtitle" name="hero_subtitle" value="<?= htmlspecialchars($hero_subtitle ?? '') ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="hero_desc" class="form-label">Deskripsi Singkat Homepage</label>
                            <textarea class="form-control" id="hero_desc" name="hero_desc" rows="3" required><?= htmlspecialchars($hero_desc ?? '') ?></textarea>
                        </div>
                        <button type="submit" class="btn btn-success">Simpan Teks Homepage</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?> 