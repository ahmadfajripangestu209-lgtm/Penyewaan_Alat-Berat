<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-12 text-center">
            <h2 class="fw-bold mb-2">Durasi Sewa</h2>
            <p class="lead">Kelola durasi sewa untuk alat yang sudah dibayar dan terverifikasi.</p>
        </div>
    </div>
    <div class="row mb-4">
        <div class="col-12 text-center">
            <?php $currentFilter = $filter ?? 'all'; ?>
            <div class="btn-group" role="group" aria-label="Filter Durasi Sewa">
                <a href="<?= base_url('admin/durasi-sewa') ?>" class="btn btn-sm <?= $currentFilter === 'all' ? 'btn-primary' : 'btn-outline-primary' ?>">Semua</a>
                <a href="<?= base_url('admin/durasi-sewa') . '?filter=aktif' ?>" class="btn btn-sm <?= $currentFilter === 'aktif' ? 'btn-primary' : 'btn-outline-primary' ?>">Aktif</a>
                <a href="<?= base_url('admin/durasi-sewa') . '?filter=selesai' ?>" class="btn btn-sm <?= $currentFilter === 'selesai' ? 'btn-primary' : 'btn-outline-primary' ?>">Selesai</a>
                <a href="<?= base_url('admin/durasi-sewa') . '?filter=belum' ?>" class="btn btn-sm <?= $currentFilter === 'belum' ? 'btn-primary' : 'btn-outline-primary' ?>">Belum Dimulai</a>
            </div>
        </div>
    </div>
    <div class="row g-4">
        <?php if (empty($pembayaran)): ?>
            <div class="col-12">
                <div class="alert alert-info">Tidak ada pembayaran terverifikasi.</div>
            </div>
        <?php else: ?>
            <?php foreach ($pembayaran as $p): ?>
                <div class="col-md-6" id="card-<?= esc($p['id']) ?>">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <?php $r_unix = !empty($p['rental_end_at']) ? strtotime($p['rental_end_at']) : null; ?>
                            <?php
                                $now = time();
                                if (empty($r_unix)) {
                                    $initialStatus = 'Belum dimulai';
                                    $initialClass = 'bg-secondary text-white';
                                } else if ($r_unix > $now) {
                                    $initialStatus = 'Aktif';
                                    $initialClass = 'bg-success text-white';
                                } else {
                                    $initialStatus = 'Selesai';
                                    $initialClass = 'bg-danger text-white';
                                }
                            ?>
                            <h5 class="card-title d-flex align-items-center justify-content-between">
                                <span><?= esc($p['nama_alat'] ?? 'Alat Berat') ?></span>
                                <span id="badge-<?= esc($p['id']) ?>" class="badge <?= $initialClass ?>"><?= $initialStatus ?></span>
                            </h5>
                            <p class="card-text small text-muted">Pemesan: <?= esc($p['nama_pemesan'] ?? ($p['nama_pemesan'] ?? '-')) ?></p>
                            <p class="mb-2">Lama Sewa user: <strong><?= esc($p['lama_sewa'] ?? ($p['lama_sewa'] ?? '-')) ?> jam</strong></p>
                            <div class="mb-3">
                                <div><strong>Sisa Waktu:</strong></div>
                                <div class="h4 text-success mt-2" id="timer-<?= esc($p['id']) ?>">Belum dimulai</div>
                            </div>
                            <div class="d-flex gap-2">
                                <?php $r_unix_attr = !empty($p['rental_end_at']) ? strtotime($p['rental_end_at']) : ''; ?>
                                <button
                                    id="start-btn-<?= esc($p['id']) ?>"
                                    class="btn btn-success btn-start<?= $initialStatus === 'Selesai' ? ' d-none' : '' ?>"
                                    data-id="<?= esc($p['id']) ?>"
                                    data-lama="<?= esc($p['lama_sewa'] ?? 0) ?>"
                                    data-rental-unix="<?= esc($r_unix_attr) ?>"
                                >Mulai Timer</button>
                                <?php if ($initialStatus === 'Selesai' && (!empty($p['alat_status']) && $p['alat_status'] === 'dipinjam')): ?>
                                    <button type="button" class="btn btn-outline-primary btn-tersedia" data-id="<?= esc($p['id']) ?>">
                                        <i class="fas fa-check-circle"></i> Tersedia
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<script>
    // Helper to format remaining seconds
    function formatDuration(seconds) {
        if (seconds <= 0) return '00:00:00';
        const d = Math.floor(seconds / 86400);
        seconds %= 86400;
        const h = Math.floor(seconds / 3600).toString().padStart(2, '0');
        const m = Math.floor((seconds % 3600) / 60).toString().padStart(2, '0');
        const s = Math.floor(seconds % 60).toString().padStart(2, '0');
        return (d > 0 ? (d + 'd ') : '') + h + ':' + m + ':' + s;
    }

    // Start button handler
    document.querySelectorAll('.btn-start').forEach(btn => {
        btn.addEventListener('click', async function () {
            const id = this.dataset.id;
            const lama = parseFloat(this.dataset.lama) || 0;
            // Ask backend to set rental_end_at based on lama (hours)
            try {
                // disable button while processing
                this.disabled = true;
                // optimistic: start local timer immediately so UI doesn't stay 'Belum dimulai'
                const optimisticEnd = Math.floor(Date.now() / 1000) + Math.floor(lama * 3600);
                initTimer(id, null, optimisticEnd);

                const res = await fetch('<?= base_url('admin/pembayaran/aktifkan-timer/') ?>' + id, {
                    method: 'POST',
                    credentials: 'same-origin',
                    headers: {'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json'},
                    body: JSON.stringify({lama: lama})
                });
                const json = await res.json();
                if (json.success) {
                    // if server returns a definitive unix timestamp, re-init timer with authoritative value
                    if (json.rental_end_at_unix) {
                        initTimer(id, json.rental_end_at, json.rental_end_at_unix);
                    }
                } else {
                    alert(json.message || 'Gagal mengaktifkan timer');
                }
            } catch (e) {
                console.error(e);
                alert('Terjadi kesalahan jaringan');
            } finally {
                this.disabled = false;
            }
        });
    });

    // Initialize timers for cards that have rental_end_at (called after activation)
    function initTimer(id, isoEndAt, unixEnd) {
        const el = document.getElementById('timer-' + id);
        if (!el) return;
        const startBtn = document.getElementById('start-btn-' + id);
        // clear any existing interval stored on element
        if (el._interval) clearInterval(el._interval);
        // update badge to Aktif
        const badge = document.getElementById('badge-' + id);
        if (badge) {
            badge.textContent = 'Aktif';
            badge.className = 'badge bg-success text-white';
        }
        function tick() {
            const nowUnix = Math.floor(Date.now() / 1000);
            const endUnix = unixEnd || Math.floor(new Date(isoEndAt).getTime() / 1000);
            const diff = endUnix - nowUnix;
            el.textContent = formatDuration(diff);
            if (diff <= 0) {
                clearInterval(el._interval);
                el.textContent = 'Waktu sewa selesai';
                if (badge) {
                    badge.textContent = 'Selesai';
                    badge.className = 'badge bg-danger text-white';
                }
                if (startBtn) {
                    startBtn.classList.add('d-none');
                }
            }
        }
        tick();
        el._interval = setInterval(tick, 1000);
    }
    // When page loads, initialize timers from data attributes present on buttons (server-side rendered)
    (function initFromDataAttrs() {
        document.querySelectorAll('.btn-start').forEach(b => {
            const id = b.dataset.id;
            const rentalUnix = b.dataset.rentalUnix ? parseInt(b.dataset.rentalUnix, 10) : null;
            const rentalIso = null; // we don't need ISO here
            if (rentalUnix) {
                initTimer(id, rentalIso, rentalUnix);
            }
        });
    })();

    // Handler for marking alat as tersedia when sewa selesai
    document.querySelectorAll('.btn-tersedia').forEach(btn => {
        btn.addEventListener('click', async function () {
            const id = this.dataset.id;
            if (!confirm('Tandai alat terkait pembayaran ini sebagai Tersedia?')) return;
            try {
                const res = await fetch('<?= base_url('admin/pembayaran/tersedia/') ?>' + id, {
                    method: 'POST',
                    credentials: 'same-origin',
                    headers: {'Accept': 'application/json'}
                });
                const json = await res.json();
                if (json.success) {
                    alert(json.message || 'Status alat diperbarui');
                    window.location.reload();
                } else {
                    alert(json.message || 'Gagal memperbarui status alat');
                }
            } catch (e) {
                console.error(e);
                alert('Terjadi kesalahan jaringan');
            }
        });
    });

    // (Deleted) previously had confirm/delete handlers; delete button removed from UI
</script>

<?= $this->endSection() ?>
