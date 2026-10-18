<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="card shadow-lg border-0">
                <div class="card-body p-4">
                    <h2 class="mb-4 fw-bold text-primary"><i class="fas fa-file-invoice me-2 text-warning"></i>Minta Penawaran Sewa Alat Berat</h2>
                    <?php if (!session('user_id')): ?>
                        <div class="alert alert-warning text-center">
                            Anda harus <a href="<?= base_url('login-user?return_url=' . urlencode(current_url())) ?>" class="btn btn-sm btn-warning ms-1">Login</a> terlebih dahulu untuk mengisi form penawaran.
                        </div>
                    <?php else: ?>
                        <?php if (session('message')): ?>
                            <script>
                                window.onload = function() {
                                    alert('Permintaan penawaran anda telah diajukan');
                                }
                            </script>
                        <?php endif; ?>
                        <?php if (session('error')): ?>
                            <div class="alert alert-danger">
                                <?= session('error') ?>
                            </div>
                        <?php endif; ?>
                        <form action="<?= base_url('alat-berat/minta-penawaran') ?>" method="post">
                            <input type="hidden" name="alat_berat_id" value="<?= $alat['id'] ?>">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Nama Alat Berat</label>
                                <input type="text" class="form-control bg-light" value="<?= esc($alat['nama']) ?>" readonly>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Harga Sewa (Per Jam)</label>
                                <input type="text" class="form-control bg-light" value="<?= !empty($alat['harga_sewa']) && is_numeric($alat['harga_sewa']) ? 'Rp ' . number_format((float)$alat['harga_sewa'], 0, ',', '.') : 'Rp 0' ?>" readonly>
                            </div>
                            <div class="mb-3">
                                <label for="telepon_pengunjung" class="form-label fw-semibold">Nomor Telepon</label>
                                <input type="text" class="form-control" id="telepon_pengunjung" name="telepon_pengunjung" 
                                       pattern="[0-9]+" 
                                       inputmode="numeric" 
                                       placeholder="Contoh: 08123456789" 
                                       maxlength="15"
                                       oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                       onkeypress="return /[0-9]/.test(event.key)"
                                       required>
                            </div>
                            <div class="mb-3">
                                <label for="alamat_pengiriman" class="form-label fw-semibold">Alamat Pengiriman</label>
                                <textarea class="form-control" id="alamat_pengiriman" name="alamat_pengiriman" rows="2" placeholder="Tulis alamat lengkap pengiriman alat berat..." required></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="lama_sewa" class="form-label fw-semibold">Lama Sewa (Jam)</label>
                                <input type="number" class="form-control" id="lama_sewa" name="lama_sewa" min="8" step="1" required>
                                <div id="lama_sewa_feedback" class="form-text text-danger d-none" aria-live="polite">Lama sewa minimal 8 jam. Silakan masukkan 8 jam atau lebih.</div>
                            </div>
                            <div class="mb-3">
                                <?php 
                                    $maxHarga = !empty($alat['harga_sewa']) && is_numeric($alat['harga_sewa']) ? (float)$alat['harga_sewa'] : 0;
                                    $maxAttr = $maxHarga > 0 ? 'max="' . $maxHarga . '"' : '';
                                    $maxText = $maxHarga > 0 ? ' s/d Rp ' . number_format($maxHarga, 0, ',', '.') : '';
                                    $minHarga = !empty($alat['min_harga_penawaran']) && is_numeric($alat['min_harga_penawaran']) ? (float)$alat['min_harga_penawaran'] : 150000;
                                ?>
                                <label for="harga_ditawarkan" class="form-label fw-semibold">Harga Ditawarkan (Rp) <span class="small text-muted">(Per Jam)</span></label>
                                <input type="number" class="form-control" id="harga_ditawarkan" name="harga_ditawarkan" min="<?= $minHarga ?>" <?= $maxAttr ?> step="5000" required>
                                <div id="harga_ditawarkan_feedback" class="form-text text-danger d-none" aria-live="polite">Nominal tawaran tidak valid. Silakan masukkan nominal tawaran yang lebih tinggi.</div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Harga Operator (Rp) <span class="small text-muted">(Per Jam)</span></label>
                                <input type="text" class="form-control bg-light" value="<?= !empty($alat['operator']) ? (is_numeric($alat['operator']) ? 'Rp ' . number_format((float)$alat['operator'], 0, ',', '.') : $alat['operator']) : 'Rp 0' ?>" readonly>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Total Estimasi Harga</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light fw-bold">Rp</span>
                                    <input type="text" class="form-control bg-light fw-bold text-dark fs-5" id="total_harga_display" value="0" readonly>
                                </div>
                                <div class="form-text text-muted">Total = (Harga Ditawarkan + Harga Operator) × Lama Sewa</div>
                                <div class="form-text text-muted">Uang Jaminan: Rp 3.000.000 dibayarkan saat penyelesaian transaksi.</div>
                                <div class="form-text text-muted">Total saat pelunasan = Total Estimasi + Uang Jaminan.</div>
                            </div>
                            <div class="alert alert-warning">
                                <div class="fw-semibold mb-1"><i class="fas fa-info-circle me-2 text-danger"></i>Ketentuan Denda Penyewaan</div>
                                <div class="small">
                                    Denda dibayarkan bersamaan dengan pelunasan sewa setelah alat dikembalikan dan dicek. Total pembayaran meliputi biaya sewa, denda keterlambatan (jika ada), serta biaya kerusakan atau pelanggaran.
                                    <br><br>
                                    Uang jaminan (deposit) sebesar Rp3.000.000 digunakan untuk menutup denda apabila terjadi pelanggaran. Jika terjadi kerusakan berat yang memerlukan estimasi bengkel, denda dapat dibayarkan setelahnya dengan pemotongan deposit atau perjanjian tambahan.
                                    <br><br>
                                    Ketentuan denda berlaku sesuai perjanjian sewa.
                                </div>
                            </div>
                            <button type="submit" class="btn btn-warning w-100 fw-bold py-2"><i class="fas fa-paper-plane me-2"></i>Kirim Permintaan Penawaran</button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Tambahan validasi untuk memastikan input telepon hanya angka
document.addEventListener('DOMContentLoaded', function() {
    const teleponInput = document.getElementById('telepon_pengunjung');
    const hargaInput = document.getElementById('harga_ditawarkan');
    const hargaFeedback = document.getElementById('harga_ditawarkan_feedback');
    const lamaInput = document.getElementById('lama_sewa');
    const lamaFeedback = document.getElementById('lama_sewa_feedback');
    const totalDisplay = document.getElementById('total_harga_display');
    
    // Cek apakah element telepon ada (user sudah login)
    if (teleponInput) {
        // Mencegah paste text yang mengandung huruf
        teleponInput.addEventListener('paste', function(e) {
            e.preventDefault();
            let paste = (e.clipboardData || window.clipboardData).getData('text');
            paste = paste.replace(/[^0-9]/g, ''); // Hapus semua karakter selain angka
            this.value = paste;
        });
        
        // Validasi real-time saat mengetik
        teleponInput.addEventListener('input', function(e) {
            // Hapus semua karakter selain angka
            this.value = this.value.replace(/[^0-9]/g, '');
            
            // Batas maksimal 15 digit
            if (this.value.length > 15) {
                this.value = this.value.slice(0, 15);
            }
        });
    }
    
    function formatRupiah(num) {
        const n = isFinite(num) ? Math.round(num) : 0;
        return new Intl.NumberFormat('id-ID').format(n);
    }
    function validateLamaSewa() {
        if (!lamaInput || !lamaFeedback) return;
        const lamaValue = lamaInput.value.trim();
        const lama = parseInt(lamaValue, 10);
        const invalid = lamaValue !== '' && (!Number.isFinite(lama) || lama < 8);

        if (invalid) {
            lamaInput.classList.add('is-invalid');
            lamaInput.setCustomValidity('Lama sewa minimal 8 jam.');
            lamaFeedback.classList.remove('d-none');
        } else {
            lamaInput.classList.remove('is-invalid');
            lamaInput.setCustomValidity('');
            lamaFeedback.classList.add('d-none');
        }
    }
    function validateHargaDitawarkan() {
        if (!hargaInput || !hargaFeedback) return;
        const value = hargaInput.value.trim();
        const harga = parseFloat(value);
        const min = parseFloat(hargaInput.min) || 0;
        const max = hargaInput.max ? parseFloat(hargaInput.max) : null;
        let invalid = value !== '' && (!isFinite(harga) || harga < min);
        if (!invalid && max !== null) {
            invalid = harga > max;
        }

        if (invalid) {
            hargaInput.classList.add('is-invalid');
            hargaInput.setCustomValidity('Nominal tawaran tidak valid. Silakan masukkan nominal tawaran yang lebih tinggi.');
            hargaFeedback.classList.remove('d-none');
        } else {
            hargaInput.classList.remove('is-invalid');
            hargaInput.setCustomValidity('');
            hargaFeedback.classList.add('d-none');
        }
    }
    function updateTotal() {
        const harga = parseFloat(hargaInput?.value) || 0;
        const lama = parseFloat(lamaInput?.value) || 0;
        const operator = <?= json_encode((float)($alat['operator'] ?? 0)) ?>;
        const total = (harga + (isFinite(operator) ? operator : 0)) * lama;
        if (totalDisplay) totalDisplay.value = formatRupiah(total);
    }
    if (hargaInput) {
        hargaInput.addEventListener('input', function() {
            validateHargaDitawarkan();
            updateTotal();
        });
        validateHargaDitawarkan();
    }
    if (lamaInput) {
        lamaInput.addEventListener('input', function() {
            validateLamaSewa();
            updateTotal();
        });
        validateLamaSewa();
    }
    updateTotal();
});
</script>

<?= $this->endSection() ?> 
