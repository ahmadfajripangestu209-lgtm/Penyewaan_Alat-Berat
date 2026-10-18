<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <!-- Avatar dihapus sesuai permintaan -->
                        <h2 class="card-title fw-bold text-primary mb-1">
                            <i class="fas fa-user-circle me-2"></i>Profil Saya
                        </h2>
                        <div class="text-muted mb-2">Kelola data akun Anda dengan mudah</div>
                    </div>

                    <?php if (session('message')): ?>
                        <div class="alert alert-success"><?= session('message') ?></div>
                    <?php endif; ?>

                    <?php if (session('error')): ?>
                        <div class="alert alert-danger"><?= session('error') ?></div>
                    <?php endif; ?>

                    <form action="<?= base_url('profile/update') ?>" method="post">
                        <div class="mb-3 input-group">
                            <span class="input-group-text bg-light"><i class="fas fa-user"></i></span>
                            <input type="text" class="form-control" id="name" name="name" value="<?= esc($user['name']) ?>" required placeholder="Nama Lengkap">
                        </div>

                        <div class="mb-3 input-group">
                            <span class="input-group-text bg-light"><i class="fas fa-envelope"></i></span>
                            <input type="email" class="form-control" id="email" name="email" value="<?= esc($user['email']) ?>" required placeholder="Email">
                        </div>

                        <div class="mb-3 input-group">
                            <span class="input-group-text bg-light"><i class="fas fa-phone"></i></span>
                            <input type="tel" class="form-control" id="phone" name="phone" value="<?= esc($user['phone'] ?? '') ?>" placeholder="Contoh: 081234567890">
                        </div>

                        <div class="mb-3 input-group">
                            <span class="input-group-text bg-light"><i class="fas fa-map-marker-alt"></i></span>
                            <textarea class="form-control" id="address" name="address" rows="2" placeholder="Alamat lengkap..."><?= esc($user['address'] ?? '') ?></textarea>
                        </div>

                        <hr class="my-4">
                        <div class="mb-3">
                            <label class="form-label fw-semibold"><i class="fas fa-key me-1"></i>Ubah Password</label>
                            <p class="text-muted small mb-2">Kosongkan jika tidak ingin mengubah password</p>
                            <div class="mb-3 input-group">
                                <span class="input-group-text bg-light"><i class="fas fa-lock"></i></span>
                                <input type="password" class="form-control" id="current_password" name="current_password" placeholder="Password Saat Ini">
                            </div>
                            <div class="mb-3 input-group">
                                <span class="input-group-text bg-light"><i class="fas fa-unlock-alt"></i></span>
                                <input type="password" class="form-control" id="new_password" name="new_password" minlength="6" placeholder="Password Baru (min. 6 karakter)">
                            </div>
                        </div>
                        <div class="d-grid gap-2 mt-4">
                            <button type="submit" class="btn btn-warning btn-lg fw-bold shadow-sm">
                                <i class="fas fa-save me-2"></i>Simpan Perubahan
                            </button>
                            <a href="<?= base_url() ?>" class="btn btn-outline-secondary btn-lg">
                                <i class="fas fa-arrow-left me-2"></i>Kembali
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?> 