<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h2 class="mb-3">Edit Alat Berat</h2>
                    <?php if (session('errors')): ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                <?php foreach(session('errors') as $err): ?>
                                    <li><?= $err ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                    <form action="<?= base_url('admin/alat-berat/edit/'.$alat['id']) ?>" method="post" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama Alat Berat</label>
                            <input type="text" class="form-control" id="nama" name="nama" value="<?= old('nama', $alat['nama']) ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="merek" class="form-label">Merek</label>
                            <input type="text" class="form-control" id="merek" name="merek" value="<?= old('merek', $alat['merek'] ?? '') ?>">
                        </div>
                        <div class="mb-3">
                            <label for="kapasitas" class="form-label">Kapasitas (ton)</label>
                            <input type="number" class="form-control" id="kapasitas" name="kapasitas" value="<?= old('kapasitas', $alat['kapasitas']) ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="tersedia" <?= old('status', $alat['status'])=='tersedia'?'selected':'' ?>>Tersedia</option>
                                <option value="dipinjam" <?= old('status', $alat['status'])=='dipinjam'?'selected':'' ?>>Dipinjam</option>
                                <option value="rusak" <?= old('status', $alat['status'])=='rusak'?'selected':'' ?>>Rusak</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="deskripsi" class="form-label">Deskripsi</label>
                            <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3" placeholder="Masukkan deskripsi alat berat"><?= old('deskripsi', $alat['deskripsi'] ?? '') ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="jenis" class="form-label">Jenis Alat Berat</label>
                            <select class="form-select" id="jenis" name="jenis" required>
                                <option value="">-- Pilih Jenis --</option>
                                <option value="excavator" <?= old('jenis', $alat['jenis'])=='excavator'?'selected':'' ?>>Excavator</option>
                                <option value="vibro-roller" <?= old('jenis', $alat['jenis'])=='vibro-roller'?'selected':'' ?>>Vibro Roller</option>
                                <option value="dozer" <?= old('jenis', $alat['jenis'])=='dozer'?'selected':'' ?>>Dozer</option>
                                <option value="motor-grader" <?= old('jenis', $alat['jenis'])=='motor-grader'?'selected':'' ?>>Motor Grader</option>
                                <option value="wheel-loader" <?= old('jenis', $alat['jenis'])=='wheel-loader'?'selected':'' ?>>Wheel Loader</option>
                                <option value="backhoe-loader" <?= old('jenis', $alat['jenis'])=='backhoe-loader'?'selected':'' ?>>Backhoe Loader</option>
                                <option value="dump-truck" <?= old('jenis', $alat['jenis'])=='dump-truck'?'selected':'' ?>>Dump Truck</option>
                                <option value="dump-truck-fuso" <?= old('jenis', $alat['jenis'])=='dump-truck-fuso'?'selected':'' ?>>Dump Truck Fuso</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="tipe" class="form-label">Tipe</label>
                            <input type="text" class="form-control" id="tipe" name="tipe" value="<?= old('tipe', $alat['tipe']) ?>">
                        </div>
                        <div class="mb-3">
                            <label for="harga_sewa" class="form-label">Harga Sewa (Rp) / jam</label>
                            <input type="text" class="form-control" id="harga_sewa" name="harga_sewa" value="<?= old('harga_sewa', $alat['harga_sewa'] ?? '') ?>">
                        </div>
                        <div class="mb-3">
                            <label for="min_harga_penawaran" class="form-label">Harga Minimum Penawaran (Rp) / jam</label>
                            <input type="number" class="form-control" id="min_harga_penawaran" name="min_harga_penawaran" value="<?= old('min_harga_penawaran', $alat['min_harga_penawaran'] ?? 150000) ?>" min="0" step="5000">
                            <small class="text-muted">Digunakan sebagai batas bawah “Harga Ditawarkan (Per Jam)” di form penawaran.</small>
                        </div>
                        <div class="mb-3">
                            <label for="operator" class="form-label">Harga Operator (Rp)</label>
                            <input type="text" class="form-control" id="operator" name="operator" value="<?= old('operator', $alat['operator']) ?>">
                        </div>
                        <div class="mb-3">
                            <label for="berat_operasional" class="form-label">Berat Operasional (kg)</label>
                            <input type="text" class="form-control" id="berat_operasional" name="berat_operasional" value="<?= old('berat_operasional', $alat['berat_operasional']) ?>">
                        </div>
                        <div class="mb-3">
                            <label for="kapasitas_bucket" class="form-label">Kapasitas Bucket (m3)</label>
                            <input type="text" class="form-control" id="kapasitas_bucket" name="kapasitas_bucket" value="<?= old('kapasitas_bucket', $alat['kapasitas_bucket']) ?>">
                        </div>
                        <div class="mb-3">
                            <label for="kedalaman_gali_maksimum" class="form-label">Kedalaman Gali Maksimum (m)</label>
                            <input type="text" class="form-control" id="kedalaman_gali_maksimum" name="kedalaman_gali_maksimum" value="<?= old('kedalaman_gali_maksimum', $alat['kedalaman_gali_maksimum']) ?>">
                        </div>
                        <div class="mb-3">
                            <label for="tinggi_transportasi" class="form-label">Tinggi (Transportasi) (m)</label>
                            <input type="text" class="form-control" id="tinggi_transportasi" name="tinggi_transportasi" value="<?= old('tinggi_transportasi', $alat['tinggi_transportasi']) ?>">
                        </div>
                        <div class="mb-3">
                            <label for="panjang_transportasi" class="form-label">Panjang (Transportasi) (m)</label>
                            <input type="text" class="form-control" id="panjang_transportasi" name="panjang_transportasi" value="<?= old('panjang_transportasi', $alat['panjang_transportasi']) ?>">
                        </div>
                        <div class="mb-3">
                            <label for="lebar_transportasi" class="form-label">Lebar (Transportasi) (m)</label>
                            <input type="text" class="form-control" id="lebar_transportasi" name="lebar_transportasi" value="<?= old('lebar_transportasi', $alat['lebar_transportasi']) ?>">
                        </div>
                        <div class="mb-3">
                            <label for="foto" class="form-label">Foto Alat Berat</label>
                            <?php if (!empty($alat['gambar'])): ?>
                                <div class="mb-2">
                                    <img id="preview-img" src="<?= base_url('images/alat_berat/'.$alat['gambar']) ?>" alt="foto" style="height:60px;max-width:100px;object-fit:contain;">
                                </div>
                            <?php else: ?>
                                <div class="mb-2">
                                    <img id="preview-img" src="#" alt="Preview" style="height:60px;max-width:100px;object-fit:contain;display:none;">
                                </div>
                            <?php endif; ?>
                            <input type="file" class="form-control" id="foto" name="foto" accept="image/*" onchange="previewFoto(event)">
                            <small class="text-muted">Kosongkan jika tidak ingin mengganti foto.</small>
                        </div>
                        <script>
                        function previewFoto(event) {
                            const [file] = event.target.files;
                            const img = document.getElementById('preview-img');
                            if (file) {
                                img.src = URL.createObjectURL(file);
                                img.style.display = 'block';
                            } else {
                                img.src = '#';
                                img.style.display = 'none';
                            }
                        }
                        </script>
                        <a href="<?= base_url('admin/alat-berat') ?>" class="btn btn-secondary">Kembali</a>
                        <button type="submit" class="btn btn-warning">Update</button>
                    </form>
                </div>
            </div>
            <!-- Manajemen Gambar Alat Berat -->
            <div class="card shadow-sm mt-4">
                <div class="card-body">
                    <h4 class="mb-3">Galeri Gambar Alat Berat</h4>
                    <!-- Form Upload Multiple Gambar -->
                    <form action="<?= base_url('admin/alat-berat/upload-gambar/'.$alat['id']) ?>" method="post" enctype="multipart/form-data" class="mb-3">
                        <div class="mb-3">
                            <label for="gambar" class="form-label">Upload Gambar (bisa lebih dari satu)</label>
                            <input type="file" class="form-control" id="gambar" name="gambar[]" accept="image/*" multiple required>
                        </div>
                        <button type="submit" class="btn btn-primary">Upload</button>
                    </form>
                    <!-- Galeri Gambar -->
                    <div class="row">
                        <?php
                        $gambarModel = new \App\Models\AlatBeratGambarModel();
                        $galeri = $gambarModel->getByAlatBerat($alat['id']);
                        ?>
                        <?php if (empty($galeri)): ?>
                            <div class="col-12 text-muted">Belum ada gambar tambahan.</div>
                        <?php else: ?>
                            <?php foreach ($galeri as $g): ?>
                                <div class="col-6 col-md-3 mb-3 text-center">
                                    <div class="position-relative border rounded p-2 h-100 bg-light">
                                        <img src="<?= base_url('public/uploads/'.$g['filename']) ?>" alt="Gambar" class="img-fluid mb-2" style="max-height:100px;object-fit:contain;">
                                        <form action="<?= base_url('admin/alat-berat/hapus-gambar/'.$g['id']) ?>" method="post" onsubmit="return confirm('Hapus gambar ini?')">
                                            <button type="submit" class="btn btn-sm btn-danger w-100">Hapus</button>
                                        </form>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?> 
