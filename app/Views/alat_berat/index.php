<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="container mt-4">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Daftar Alat Berat</h4>
                    <a href="<?= base_url('alat-berat/create') ?>" class="btn btn-primary float-end">Tambah Alat Berat</a>
                </div>
                <div class="card-body">
                    <?php if (session()->getFlashdata('message')) : ?>
                        <div class="alert alert-success">
                            <?= session()->getFlashdata('message') ?>
                        </div>
                    <?php endif; ?>

                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Jenis</th>
                                    <th>Kapasitas</th>
                                    <th>Status</th>
                                    <th>Hapus</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 1; ?>
                                <?php foreach ($alat_berat as $alat) : ?>
                                    <tr>
                                        <td><?= $i++; ?></td>
                                        <td><?= $alat['nama']; ?></td>
                                        <td><?= $alat['jenis']; ?></td>
                                        <td><?= $alat['kapasitas']; ?></td>
                                        <td>
                                            <span class="badge bg-<?= $alat['status'] == 'tersedia' ? 'success' : ($alat['status'] == 'dipinjam' ? 'warning' : 'danger') ?>">
                                                <?= ucfirst($alat['status']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <a href="<?= base_url('alat-berat/edit/' . $alat['id']) ?>" class="btn btn-sm btn-warning">Edit</a>
                                            <a href="<?= base_url('alat-berat/delete/' . $alat['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">Hapus</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?> 