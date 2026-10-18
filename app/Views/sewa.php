<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>
<div class="container my-5">
    <h1 class="mb-4" style="font-weight:600;">Minta Penawaran Sewa Alat Berat</h1>
    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="card shadow-sm">
                <div class="card-body">
                    <form action="<?= base_url('sewa/proses') ?>" method="post">
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control" id="nama" name="nama" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="telepon" class="form-label">Nomor Telepon</label>
                            <input type="tel" class="form-control" id="telepon" name="telepon" pattern="[0-9]*" inputmode="numeric" placeholder="Contoh: 08123456789" required>
                        </div>
                        <div class="mb-3">
                            <label for="alat" class="form-label">Nama Alat Berat</label>
                            <input type="text" class="form-control" id="alat" name="alat" value="<?= $alat['nama'] ?? '' ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="pesan" class="form-label">Pesan / Keterangan</label>
                            <textarea class="form-control" id="pesan" name="pesan" rows="4" placeholder="Tulis kebutuhan atau permintaan khusus Anda..."></textarea>
                        </div>
                        <button type="submit" class="btn btn-warning w-100 fw-bold py-2">Kirim Permintaan Penawaran</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?> 