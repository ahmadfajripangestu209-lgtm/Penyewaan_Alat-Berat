<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h2 class="mb-3">Manajemen Alat Berat</h2>
                    <a href="<?= base_url('admin/alat-berat/tambah') ?>" class="btn btn-warning mb-3">+ Tambah Alat Berat</a>
                    <a href="<?= base_url('admin/alat-berat/hapus-semua') ?>" class="btn btn-danger mb-3 ms-2" onclick="return confirm('Yakin hapus SEMUA data alat berat? Data tidak bisa dikembalikan!')">Hapus Semua</a>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle">
                            <thead class="table-dark">
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Jenis</th>
                                    <th>Merek</th>
                                    <th>Kapasitas</th>
                                    <th>Status</th>
                                    <th>Hapus</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($alat_berat)): ?>
                                    <tr><td colspan="6" class="text-center">Belum ada data</td></tr>
                                <?php else: $i=1; foreach($alat_berat as $alat): ?>
                                    <tr>
                                        <td><?= $i++ ?></td>
                                        <td><?= esc($alat['nama']) ?></td>
                                        <td><?= esc($alat['jenis']) ?></td>
                                        <td><?= esc($alat['merek'] ?? '-') ?></td>
                                        <td><?= esc($alat['kapasitas']) ?></td>
                                        <td><?= esc($alat['status']) ?></td>
                                        <td>
                                            <a href="<?= base_url('admin/alat-berat/edit/'.$alat['id']) ?>" class="btn btn-sm btn-primary">Edit</a>
                                            <a href="<?= base_url('admin/alat-berat/hapus/'.$alat['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus data ini?')">Hapus</a>
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