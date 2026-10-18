<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h3 class="mb-4 text-center">Lupa Password</h3>
                    <?php if (session('message')): ?>
                        <div class="alert alert-success"> <?= session('message') ?> </div>
                    <?php endif; ?>
                    <?php if (session('error')): ?>
                        <div class="alert alert-danger"> <?= session('error') ?> </div>
                    <?php endif; ?>
                    <form action="<?= base_url('lupa-password') ?>" method="post">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required placeholder="Masukkan email anda">
                        </div>
                        <button type="submit" class="btn btn-warning w-100">Kirim Link Reset</button>
                    </form>
                    <div class="mt-3 text-center">
                        <a href="<?= base_url('login-user') ?>">Kembali ke Login</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?> 