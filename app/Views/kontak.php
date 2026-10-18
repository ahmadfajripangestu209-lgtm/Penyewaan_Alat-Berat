<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>
<style>
    body {
        background: #f8fafc;
    }
    .contact-card {
        transition: box-shadow 0.2s;
        border-radius: 18px;
    }
    .contact-card:hover {
        box-shadow: 0 8px 32px rgba(0,0,0,0.12);
    }
    .contact-icon {
        width: 38px;
        height: 38px;
        background: #fff3cd;
        color: #f59e42;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
        margin-right: 14px;
        box-shadow: 0 2px 8px rgba(245,158,66,0.08);
    }
</style>
<div class="container my-5">
    <h1 class="mb-4" style="font-weight:700;letter-spacing:1px;">Kontak Kami</h1>
    <div class="row g-4 align-items-stretch">
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm contact-card h-100 border-0">
                <div class="card-body">
                    <h3 class="mb-3 text-warning" style="font-weight:600;">Edi Jaya Jambi</h3>
                    <ul class="list-unstyled mb-0">
                        <li class="d-flex align-items-center mb-3">
                            <span class="contact-icon"><i class="fas fa-map-marker-alt"></i></span>
                            <div>
                                <div class="fw-bold">Alamat</div>
                                <div class="text-muted small">Tanjung Mulya, Kec. Bahar Sel., Kabupaten Muaro Jambi, Jambi 30756</div>
                            </div>
                        </li>
                        <li class="d-flex align-items-center mb-3">
                            <span class="contact-icon"><i class="fas fa-phone"></i></span>
                            <div>
                                <div class="fw-bold">Telepon</div>
                                <a href="tel:+6288707077027" class="text-decoration-none text-dark">+62 887-0707-7027</a>
                            </div>
                        </li>
                        <li class="d-flex align-items-center mb-3">
                            <span class="contact-icon"><i class="fas fa-envelope"></i></span>
                            <div>
                                <div class="fw-bold">Email</div>
                                <a href="mailto:edijayajambi@email.com" class="text-decoration-none text-dark">edijayajambi@email.com</a>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm contact-card h-100 border-0">
                <div class="card-body p-2">
                    <div style="width: 100%">
                        <iframe width="100%" height="300" frameborder="0" style="border:0;border-radius:12px;" allowfullscreen
                            src="https://www.google.com/maps/embed?origin=mfe&pb=!1m2!2m1!1sSEWA+ALAT+BERAT+ADI+JAYA,+WG6F%2B9WC,+Tanjung+Mulya,+Bahar+Selatan,+Muaro+Jambi,+Jambi"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?> 