<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>
<div class="container my-5" style="max-width:800px;">
    <div class="card shadow-sm border-0">
        <div class="card-body p-4">
            <h2 class="fw-bold mb-3"><i class="fas fa-money-bill-wave me-2 text-success"></i>Transaksi Pembayaran</h2>
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
                            <div><strong>Bank:</strong> BCA</div>
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

            <form action="<?= base_url('pembayaran/proses') ?>" method="post" enctype="multipart/form-data">
                <input type="hidden" name="riwayat_penawaran_id" value="<?= esc($riwayat['id']) ?>">
                
                <div class="mb-3">
                    <label class="form-label">Nama Alat</label>
                    <input type="text" class="form-control" name="nama_alat" value="<?= esc($alat['nama'] ?? '-') ?>" readonly>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Rincian Pembayaran</label>
                    <div class="bg-light p-3 rounded">
                        <?php $lama = (int)($riwayat['lama_sewa'] ?? 0); ?>
                        <?php $biayaOperator = (int)($harga_operator ?? 0) * $lama; ?>
                        <?php $biayaSewa = (int)($total_estimasi ?? 0) - $biayaOperator; ?>
                        <div class="d-flex justify-content-between">
                            <span>Biaya Sewa</span>
                            <span class="fw-bold"><?= 'Rp ' . number_format($biayaSewa, 0, ',', '.') ?></span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Biaya Operator</span>
                            <span class="fw-bold"><?= 'Rp ' . number_format($biayaOperator, 0, ',', '.') ?></span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Uang Jaminan</span>
                            <span class="fw-bold"><?= 'Rp ' . number_format(3000000, 0, ',', '.') ?></span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between">
                            <span class="fw-semibold">Total Pembayaran</span>
                            <?php $totalBayar = ((int)($total_estimasi ?? 0)) + 3000000; ?>
                            <span class="fw-bold text-success fs-5"><?= 'Rp ' . number_format($totalBayar, 0, ',', '.') ?></span>
                        </div>
                    </div>
                    <input type="hidden" name="jumlah" value="<?= esc((int)($total_estimasi ?? 0)) ?>">
                    <input type="hidden" name="deposit" value="3000000">
                </div>

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
                        <select class="form-select manual-input" name="bank_tujuan">
                            <option value="BCA">BCA</option>
                            <option value="Mandiri">Mandiri</option>
                            <option value="BRI">BRI</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Bukti Transfer</label>
                        <input type="file" class="form-control manual-input" name="bukti_transfer" accept="image/*">
                    </div>
                </div>

                <button type="submit" class="btn btn-success w-100 fs-5 fw-bold py-2"><i class="fas fa-check-circle me-2"></i>Lanjutkan Pembayaran</button>
            </form>

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
        </div>
    </div>
    <div class="alert alert-info mt-3" id="paymentStatusAlert">Setelah melakukan pembayaran instan, status Anda akan terverifikasi secara otomatis.</div>
    <a href="<?= base_url('alat-berat/riwayat-penawaran') ?>" class="btn btn-outline-secondary mt-2">Kembali</a>
    </div>
<?= $this->endSection() ?>
