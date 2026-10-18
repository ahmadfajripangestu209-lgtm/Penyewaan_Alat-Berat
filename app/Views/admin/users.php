<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h2 class="mb-3">Manajemen Pengguna</h2>
                    <?php if (session('message')): ?>
                        <div class="alert alert-success"> <?= session('message') ?> </div>
                    <?php endif; ?>
                    <?php if (session('error')): ?>
                        <div class="alert alert-danger"> <?= session('error') ?> </div>
                    <?php endif; ?>
                    <a href="<?= base_url('admin/users/tambah') ?>" class="btn btn-warning mb-3">+ Tambah User</a>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle">
                            <thead class="table-dark">
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Dibuat</th>
                                    <th>Hapus</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($users)): ?>
                                    <tr><td colspan="6" class="text-center">Belum ada user</td></tr>
                                <?php else: $i=1; foreach($users as $user): ?>
                                    <tr>
                                        <td><?= $i++ ?></td>
                                        <td><?= esc($user['name']) ?></td>
                                        <td><?= esc($user['email']) ?></td>
                                        <td><span class="badge bg-<?= $user['role']==='admin'?'warning text-dark':'secondary' ?>"> <?= esc($user['role']) ?> </span></td>
                                        <td><?php
                                            $dt = new DateTime($user['created_at'], new DateTimeZone('UTC'));
                                            $dt->setTimezone(new DateTimeZone('Asia/Jakarta'));
                                            echo $dt->format('d-m-Y H:i');
                                        ?> WIB</td>
                                        <td>
                                            <a href="<?= base_url('admin/users/edit/'.$user['id']) ?>" class="btn btn-sm btn-primary">Edit</a>
                                            <a href="<?= base_url('admin/users/hapus/'.$user['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus user ini?')">Hapus</a>
                                        </td>
                                    </tr>
                                <?php endforeach; endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?> 