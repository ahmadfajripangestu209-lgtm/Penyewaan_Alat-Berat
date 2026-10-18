<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Edi Jaya - Penyewaan Alat Berat' ?></title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #FFD700;
            --secondary-color: #000000;
        }
        .navbar-brand {
            font-weight: bold;
            font-size: 1.5rem;
        }
        .nav-link {
            font-weight: 500;
        }
        .btn, .btn-warning, .btn-primary {
            transition: transform 0.15s, box-shadow 0.15s, background 0.2s;
        }
        .btn:hover, .btn-warning:hover, .btn-primary:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 16px rgba(0,0,0,0.13);
        }
        .bg-primary {
            background-color: var(--secondary-color) !important;
        }
        .text-primary {
            color: var(--primary-color) !important;
        }
        .card {
            border: 1px solid rgba(0,0,0,0.1);
        }
        .card:hover {
            box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15);
        }
        .social-links a:hover {
            color: var(--primary-color) !important;
        }
        .whatsapp-float {
            position: fixed;
            right: 28px;
            bottom: 28px;
            z-index: 9999;
            width: 48px;
            height: 48px;
            background: #25d366;
            color: #fff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            box-shadow: 0 4px 16px rgba(0,0,0,0.18);
            animation: pulse 2s infinite;
        }
        .whatsapp-float:hover {
            background: #1ebe57;
            color: #fff;
            box-shadow: 0 8px 32px rgba(0,0,0,0.22);
            text-decoration: none;
            animation: bounce 0.7s;
        }
        @keyframes bounce {
            0%   { transform: translateY(0); }
            30%  { transform: translateY(-10px); }
            50%  { transform: translateY(0); }
            70%  { transform: translateY(-6px); }
            100% { transform: translateY(0); }
        }
        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(37,211,102,0.5);}
            70% { box-shadow: 0 0 0 12px rgba(37,211,102,0);}
            100% { box-shadow: 0 0 0 0 rgba(37,211,102,0);}
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
            color: #fff;
            transition: background 0.25s, color 0.25s, transform 0.18s, box-shadow 0.18s;
        }
        .btn-primary:hover, .btn-primary:focus {
            background-color: #0056b3;
            border-color: #0056b3;
            color: #fff;
            transform: scale(1.05);
            box-shadow: 0 4px 16px rgba(0,0,0,0.13);
        }
    </style>
    <style>
    html, body {
      height: 100%;
    }
    body {
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }
    .main-content {
      flex: 1 0 auto;
    }
    footer {
      flex-shrink: 0;
    }
    @media print {
      .no-print {
        display: none !important;
      }
    }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="<?= base_url() ?>">
                <i class="fas fa-truck-monster text-warning me-2"></i> Edi Jaya Jambi
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url() ?>">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('alat-berat') ?>">Alat Berat</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('tentang') ?>">Tentang Kami</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('kontak') ?>">Kontak</a>
                    </li>
                    <?php if (session('logged_in') && session('user_role') === 'admin'): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-warning fw-bold" href="#" id="adminDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Admin
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="adminDropdown">
                                <li><a class="dropdown-item" href="<?= base_url('admin') ?>">Dashboard Admin</a></li>
                                <li><a class="dropdown-item" href="<?= base_url('admin/users') ?>">Manajemen Pengguna</a></li>
                                <li><a class="dropdown-item" href="<?= base_url('admin/alat-berat') ?>">Manajemen Alat Berat</a></li>
                                <li><a class="dropdown-item" href="<?= base_url('admin/penawaran') ?>">Kelola Penawaran</a></li>
                                <li><a class="dropdown-item" href="<?= base_url('admin/pengaturan') ?>">Pengaturan</a></li>
                            </ul>
                        </li>
                    <?php endif; ?>
                    <?php if (session('logged_in')): ?>
                        <?php
                            $notifikasiModel = new \App\Models\NotifikasiModel();
                            $jumlahNotifBaru = $notifikasiModel->where('user_id', session('user_id'))->where('is_read', 0)->countAllResults();
                        ?>
                        <li class="nav-item">
                            <a class="nav-link position-relative" href="<?= base_url('notifikasi') ?>">
                                <i class="fas fa-bell"></i>
                                <?php if ($jumlahNotifBaru > 0): ?>
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                        <?= $jumlahNotifBaru ?>
                                    </span>
                                <?php endif; ?>
                            </a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-user-circle me-1"></i><?= esc(session('user_name')) ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                <?php if (session('user_role') === 'admin'): ?>
                                    <li><a class="dropdown-item" href="<?= base_url('admin') ?>"><i class="fas fa-tachometer-alt me-2"></i>Dashboard</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                <?php endif; ?>
                                <li><a class="dropdown-item" href="<?= base_url('profile') ?>"><i class="fas fa-user me-2"></i>Profil Saya</a></li>
                                <?php if (session('user_role') !== 'admin'): ?>
                                    <li><a class="dropdown-item" href="<?= base_url('alat-berat/riwayat-penawaran') ?>"><i class="fas fa-history me-2"></i>Riwayat Sewa Alat berat</a></li>
                                <?php endif; ?>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="<?= base_url('logout') ?>"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= base_url('login-user') ?>">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= base_url('register') ?>">Register</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="main-content">
      <?= $this->renderSection('content') ?>
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5> Edi Jaya Jambi</h5>
                    <p>Penyewaan alat berat terpercaya dengan kualitas terjamin dan harga kompetitif.</p>
                </div>
                <div class="col-md-4">
                    <h5>Kontak</h5>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-phone me-2"></i> +62 887-0707-7027</li>
                        <li><i class="fas fa-envelope me-2"></i> info@edijayajambi.com</li>
                        <li><i class="fas fa-map-marker-alt me-2"></i> Tanjung Mulya, Kec. Bahar Sel., Kabupaten Muaro Jambi, Jambi 30756</li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5>Ikuti Kami</h5>
                    <div class="social-links">
                        <a href="#" class="text-white me-3"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="text-white me-3"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-white me-3"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="text-white"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
            </div>
            <hr class="my-4">
            <div class="text-center">
                <p class="mb-0">&copy; <?= date('Y') ?> Edi Jaya Jambi. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Floating WhatsApp Button -->
    <a href="https://wa.me/6288707077027" target="_blank" class="whatsapp-float" title="Chat WhatsApp">
        <i class="fab fa-whatsapp"></i>
    </a>
</body>
</html> 
