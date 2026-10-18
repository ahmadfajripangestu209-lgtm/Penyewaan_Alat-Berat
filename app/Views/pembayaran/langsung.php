<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>
<div class="container my-5" style="max-width:800px;">
    <div class="card shadow-sm border-0">
        <div class="card-body p-4">
            <h2 class="fw-bold mb-3"><i class="fas fa-money-bill-wave me-2 text-success"></i>Pembayaran Langsung</h2>
            <?php if (session('error')): ?>
                <div class="alert alert-danger mb-3"><?= session('error') ?></div>
            <?php endif; ?>
            <div class="alert alert-info">
                <div class="d-flex align-items-center mb-2">
                    <i class="fas fa-university me-2 text-primary"></i>
                    <strong>Informasi Rekening Tujuan Perusahaan</strong>
                </div>
                <div class="row g-2">
                    <div class="col-md-6">
                        <div class="bg-light p-3 rounded">
                            <div><strong>Bank:</strong> BRI</div>
                            <div><strong>No. Rekening:</strong> 1234567890</div>
                            <div><strong>Atas Nama:</strong> Edi Jaya Jambi</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="bg-light p-3 rounded">
                            <div><strong>Bank:</strong> Mandiri</div>
                            <div><strong>No. Rekening:</strong> 9876543210</div>
                            <div><strong>Atas Nama:</strong> Edi Jaya Jambi</div>
                        </div>
                    </div>
                </div>
                <div class="small mt-2">Silakan transfer ke salah satu rekening di atas dan unggah bukti transfer pada form berikut.</div>
            </div>
            <div class="alert alert-warning">
                <div class="d-flex align-items-center mb-2">
                    <i class="fas fa-exclamation-circle me-2 text-danger"></i>
                    <strong>Ketentuan Denda Penyewaan</strong>
                </div>
                <div class="small text-dark">
                    Denda dibayarkan bersamaan dengan pelunasan sewa setelah alat dikembalikan dan dicek. Total pembayaran meliputi biaya sewa, denda keterlambatan (jika ada), serta biaya kerusakan atau pelanggaran.
                    <br><br>
                    Uang jaminan (deposit) sebesar Rp3.000.000 digunakan untuk menutup denda apabila terjadi pelanggaran. Jika terjadi kerusakan berat yang memerlukan estimasi bengkel, denda dapat dibayarkan setelahnya dengan pemotongan deposit atau perjanjian tambahan.
                    <br><br>
                    Ketentuan denda berlaku sesuai perjanjian sewa.
                </div>
            </div>
            <form action="<?= base_url('pembayaran/langsung') ?>" method="post" enctype="multipart/form-data" id="formBayarLangsung">
                <input type="hidden" name="alat_id" value="<?= esc($alat['id']) ?>">
                <div class="mb-3">
                    <label class="form-label">Nama Alat</label>
                    <input type="text" class="form-control" name="nama_alat" value="<?= esc($alat['nama'] ?? '-') ?>" readonly>
                </div>
                <div class="mb-3">
                    <label class="form-label">Harga Sewa (per jam)</label>
                    <input type="text" class="form-control" value="<?= 'Rp ' . number_format((int)($harga_unit ?? 0), 0, ',', '.') ?>" readonly>
                </div>
                <div class="mb-3">
                    <label class="form-label">Harga Operator (per jam)</label>
                    <input type="text" class="form-control" value="<?= 'Rp ' . number_format((int)($harga_operator ?? 0), 0, ',', '.') ?>" readonly>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Lama Sewa (jam)</label>
                    <input type="number" class="form-control" name="lama_sewa" id="lamaSewa" min="1" step="1" placeholder="Contoh: 8" required>
                    <div id="lama_sewa_feedback" class="form-text text-danger d-none" aria-live="polite">Lama sewa minimal 8 jam. Silakan masukkan 8 jam atau lebih.</div>
                </div>
                <div class="mb-3">
                    <label class="form-label">No Telepon</label>
                    <input type="text" class="form-control" name="telepon_pengunjung" placeholder="Contoh: 081234567890">
                </div>
                <div class="mb-3">
                    <label class="form-label">Alamat Pengiriman</label>
                    <textarea class="form-control" name="alamat_pengiriman" rows="2" placeholder="Alamat lengkap pengiriman alat"></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Rincian Pembayaran</label>
                    <div class="bg-light p-3 rounded">
                        <div class="d-flex justify-content-between">
                            <span>Biaya Sewa</span>
                            <span class="fw-bold" id="biayaSewa">Rp 0</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Biaya Operator</span>
                            <span class="fw-bold" id="biayaOperator">Rp 0</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Uang Jaminan</span>
                            <span class="fw-bold" id="depositDisplay"><?= 'Rp ' . number_format(3000000, 0, ',', '.') ?></span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between">
                            <span class="fw-semibold">Total Pembayaran</span>
                            <span class="fw-bold text-success fs-5" id="totalBayar">Rp 0</span>
                        </div>
                    </div>
                    <input type="hidden" name="jumlah" id="jumlahInput" value="0">
                    <input type="hidden" name="deposit" value="3000000">
                </div>
            <style>
                .payment-method-card {
                    transition: all 0.3s ease;
                    border: 2px solid #dee2e6;
                }
                .payment-method-card:hover {
                    transform: translateY(-2px);
                    box-shadow: 0 4px 8px rgba(0,0,0,0.05);
                    border-color: #0d6efd;
                }
                .payment-method-card.active {
                    border-color: #198754 !important;
                    background-color: rgba(25, 135, 84, 0.03);
                }
                .cursor-pointer {
                    cursor: pointer;
                }
            </style>

            <!-- Pilihan Metode Pembayaran -->
            <div class="mb-4">
                <label class="form-label fw-semibold">Pilih Metode Pembayaran</label>
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="card payment-method-card border-2 cursor-pointer p-3 text-center h-100 active" id="cardPakasir" onclick="selectPaymentMethod('pakasir')">
                            <div class="card-body p-0">
                                <div class="fs-4 mb-2 text-success"><i class="fas fa-bolt"></i></div>
                                <h6 class="fw-bold mb-1">Pembayaran Instan (Pakasir)</h6>
                                <small class="text-muted d-block">QRIS / Virtual Account</small>
                                <span class="badge bg-success mt-2">Otomatis / Instan</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card payment-method-card border-2 cursor-pointer p-3 text-center h-100" id="cardManual" onclick="selectPaymentMethod('manual')">
                            <div class="card-body p-0">
                                <div class="fs-4 mb-2 text-primary"><i class="fas fa-university"></i></div>
                                <h6 class="fw-bold mb-1">Transfer Bank Manual</h6>
                                <small class="text-muted d-block">Mandiri / BRI / BCA</small>
                                <span class="badge bg-secondary mt-2">Verifikasi 1-24 Jam</span>
                            </div>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="metode_pembayaran" id="metodePembayaranInput" value="pakasir">
            </div>

            <!-- Bagian Form Transfer Bank Manual -->
            <div id="manualFieldsSection" style="display: none;">
                <div class="mb-3">
                    <label class="form-label">Bank Pengirim</label>
                    <input type="text" class="form-control manual-input" name="bank_pengirim" placeholder="Contoh: BCA, BRI, Mandiri">
                </div>
                <div class="mb-3">
                    <label class="form-label">Nama Pengirim</label>
                    <input type="text" class="form-control manual-input" name="nama_pengirim" placeholder="Nama Pemilik Rekening">
                </div>
                <div class="mb-3">
                    <label class="form-label">Bank Tujuan</label>
                    <select class="form-select manual-input" name="bank_tujuan" id="bankTujuanSelect">
                        <option value="Mandiri">Mandiri</option>
                        <option value="BRI">BRI</option>
                    </select>
                    <div class="form-text mt-1">
                        No. Rekening: <span id="noRekTujuan">9876543210</span><br>
                        Atas Nama: <span id="namaRekTujuan">Edi Jaya Jambi</span>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Bukti Transfer</label>
                    <input type="file" class="form-control manual-input" name="bukti_transfer" accept="image/*">
                </div>
            </div>

            <button type="submit" class="btn btn-success w-100 fs-5 fw-bold py-2"><i class="fas fa-check-circle me-2"></i>Lanjutkan Pembayaran</button>
        </form>
    </div>
</div>
<div class="alert alert-info mt-3" id="paymentStatusAlert">Setelah melakukan pembayaran instan, status Anda akan terverifikasi secara otomatis.</div>
<a href="<?= base_url('alat-berat') ?>" class="btn btn-outline-secondary mt-2">Kembali</a>

<script>
    function selectPaymentMethod(method) {
        const cardPakasir = document.getElementById('cardPakasir');
        const cardManual = document.getElementById('cardManual');
        const inputMetode = document.getElementById('metodePembayaranInput');
        const manualSection = document.getElementById('manualFieldsSection');
        const manualInputs = document.querySelectorAll('.manual-input');

        if (method === 'pakasir') {
            cardPakasir.classList.add('active');
            cardManual.classList.remove('active');
            inputMetode.value = 'pakasir';
            manualSection.style.display = 'none';
            manualInputs.forEach(input => {
                input.removeAttribute('required');
            });
        } else {
            cardManual.classList.add('active');
            cardPakasir.classList.remove('active');
            inputMetode.value = 'manual';
            manualSection.style.display = 'block';
            manualInputs.forEach(input => {
                input.setAttribute('required', 'required');
            });
        }
    }
    
    // Initialize defaults
    document.addEventListener('DOMContentLoaded', function() {
        selectPaymentMethod('pakasir');
    });
</script>

<script>
    (function(){
        const hargaUnit = <?= json_encode((int)($harga_unit ?? 0)) ?>;
        const hargaOperator = <?= json_encode((int)($harga_operator ?? 0)) ?>;
        const lamaInput = document.getElementById('lamaSewa');
        const lamaFeedback = document.getElementById('lama_sewa_feedback');
        const biayaSewaEl = document.getElementById('biayaSewa');
        const biayaOperatorEl = document.getElementById('biayaOperator');
        const totalBayarEl = document.getElementById('totalBayar');
        const jumlahInput = document.getElementById('jumlahInput');
        const bankSelect = document.getElementById('bankTujuanSelect');
        const noRekEl = document.getElementById('noRekTujuan');
        const namaRekEl = document.getElementById('namaRekTujuan');
        const rekeningMap = {
            Mandiri: { no: '9876543210', nama: 'Edi Jaya Jambi' },
            BRI: { no: '1234567890', nama: 'Edi Jaya Jambi' }
        };
        function formatIDR(n){ return new Intl.NumberFormat('id-ID').format(n); }
        function updateTotal(){
            const lama = parseInt(lamaInput.value || '0', 10);
            const durasi = isFinite(lama) ? lama : 0;
            const biayaSewa = hargaUnit * durasi;
            const biayaOp = hargaOperator * durasi;
            biayaSewaEl.textContent = 'Rp ' + formatIDR(biayaSewa);
            biayaOperatorEl.textContent = 'Rp ' + formatIDR(biayaOp);
            const jumlah = biayaSewa + biayaOp;
            jumlahInput.value = jumlah;
            const total = jumlah + 3000000;
            totalBayarEl.textContent = 'Rp ' + formatIDR(total);
        }
        function validateLamaSewa(){
            if (!lamaInput || !lamaFeedback) return;
            const lamaValue = lamaInput.value || '';
            const lama = parseInt(lamaValue, 10);
            const invalid = lamaValue !== '' && (!Number.isFinite(lama) || lama < 8);
            if (invalid){
                lamaInput.classList.add('is-invalid');
                lamaFeedback.classList.remove('d-none');
            } else {
                lamaInput.classList.remove('is-invalid');
                lamaFeedback.classList.add('d-none');
            }
        }
        function updateRekening(){
            const bank = bankSelect.value;
            const data = rekeningMap[bank] || null;
            if (data) {
                noRekEl.textContent = data.no;
                namaRekEl.textContent = data.nama;
            } else {
                noRekEl.textContent = '-';
                namaRekEl.textContent = '-';
            }
        }
        lamaInput.addEventListener('input', function(){
            validateLamaSewa();
            updateTotal();
        });
        bankSelect.addEventListener('change', updateRekening);
        validateLamaSewa();
        updateTotal();
        updateRekening();
    })();
</script>
<?= $this->endSection() ?>
