<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('alat-berat', 'AlatBerat::index');
$routes->get('alat-berat/detail/(:num)', 'AlatBerat::detail/$1');
$routes->get('alat-berat/create', 'AlatBerat::create');
$routes->post('alat-berat/store', 'AlatBerat::store');
$routes->get('alat-berat/excavator', 'AlatBerat::excavator');
$routes->get('alat-berat/vibro-roller', 'AlatBerat::vibroRoller');
$routes->get('alat-berat/dozer', 'AlatBerat::dozer');
$routes->get('alat-berat/motor-grader', 'AlatBerat::motorGrader');
$routes->get('alat-berat/wheel-loader', 'AlatBerat::wheelLoader');
$routes->get('alat-berat/backhoe-loader', 'AlatBerat::backhoeLoader');
$routes->get('alat-berat/dump-truck', 'AlatBerat::dumpTruck');
$routes->get('alat-berat/dump-truck-fuso', 'AlatBerat::dumpTruckFuso');
$routes->post('alat-berat/minta-penawaran', 'AlatBerat::mintaKuotasi');
$routes->get('alat-berat/riwayat-penawaran', 'AlatBerat::riwayatKuotasi');
$routes->post('alat-berat/riwayat-penawaran/hapus/(:num)', 'AlatBerat::hapusRiwayatKuotasi/$1');
$routes->get('alat-berat/minta-penawaran-form/(:num)', 'AlatBerat::mintaKuotasiForm/$1');
$routes->get('register', 'Auth::register');
$routes->post('register', 'Auth::registerProcess');
$routes->get('login', 'Auth::login');
$routes->post('login', 'Auth::loginProcess');
$routes->get('logout', 'Auth::logout');
$routes->get('admin', 'Admin::index');
$routes->get('admin/users', 'Admin::users');
$routes->get('admin/users/tambah', 'Admin::tambahUser');
$routes->post('admin/users/tambah', 'Admin::simpanUser');
$routes->get('admin/users/edit/(:num)', 'Admin::editUser/$1');
$routes->post('admin/users/edit/(:num)', 'Admin::updateUser/$1');
$routes->get('admin/users/hapus/(:num)', 'Admin::hapusUser/$1');
$routes->get('admin/alat-berat', 'Admin::alatBerat');
$routes->get('admin/alat-berat/tambah', 'Admin::tambahAlatBerat');
$routes->post('admin/alat-berat/tambah', 'Admin::simpanAlatBerat');
$routes->get('admin/alat-berat/edit/(:num)', 'Admin::editAlatBerat/$1');
$routes->post('admin/alat-berat/edit/(:num)', 'Admin::updateAlatBerat/$1');
$routes->get('admin/alat-berat/hapus/(:num)', 'Admin::hapusAlatBerat/$1');
$routes->post('admin/alat-berat/upload-gambar/(:num)', 'Admin::uploadGambarAlatBerat/$1');
$routes->post('admin/alat-berat/hapus-gambar/(:num)', 'Admin::hapusGambarAlatBerat/$1');
$routes->get('kontak', 'Home::kontak');
$routes->get('tentang', 'Home::tentang');
$routes->get('admin/pengaturan', 'Admin::pengaturan');
$routes->post('admin/pengaturan/upload-foto', 'Admin::uploadFotoProfil');
$routes->post('admin/pengaturan/upload-foto-header', 'Admin::uploadFotoHeader');
$routes->post('admin/pengaturan/update-hero', 'Admin::updateHero');
$routes->get('sewa', 'Sewa::index');
$routes->post('sewa/proses', 'Sewa::proses');
$routes->get('admin/penawaran', 'Admin::kelolaKuotasi');
$routes->get('admin/penawaran/hapus/(:num)', 'Admin::hapusKuotasi/$1');
$routes->post('admin/penawaran/update-status/(:num)', 'Admin::updateStatusKuotasi/$1');
$routes->get('login-admin', 'Auth::loginAdmin');
$routes->post('login-admin', 'Auth::loginAdminProcess');
$routes->get('login-user', 'Auth::loginUser');
$routes->post('login-user', 'Auth::loginUserProcess');
$routes->post('alat-berat/riwayat-penawaran/ajukan-ulang/(:num)', 'AlatBerat::ajukanUlangKuotasi/$1');
$routes->get('profile', 'Profile::index');
$routes->post('profile/update', 'Profile::update');
$routes->get('notifikasi', 'Profile::notifikasi');
$routes->get('notifikasi/baca/(:num)', 'Profile::bacaNotifikasi/$1');
$routes->post('notifikasi/hapus/(:num)', 'Profile::hapusNotifikasi/$1');
$routes->post('notifikasi/hapus-semua', 'Profile::hapusSemuaNotifikasi');
$routes->post('admin/penawaran/hapus-semua', 'Admin::hapusSemuaKuotasi');
$routes->get('lupa-password', 'Auth::lupaPassword');
$routes->post('lupa-password', 'Auth::lupaPassword');
$routes->get('admin/alat-berat/hapus-semua', 'Admin::hapusSemuaAlatBerat');

// Admin Pembayaran
$routes->get('admin/pembayaran', 'Admin::pembayaran');
$routes->post('admin/pembayaran/verifikasi/(:num)', 'Admin::verifikasiPembayaran/$1');
$routes->post('admin/pembayaran/tolak/(:num)', 'Admin::tolakPembayaran/$1');
$routes->post('admin/pembayaran/hapus/(:num)', 'Admin::hapusPembayaran/$1');
$routes->post('admin/pembayaran/dipinjam/(:num)', 'Admin::dipinjamPembayaran/$1');
$routes->post('admin/pembayaran/tersedia/(:num)', 'Admin::tersediaPembayaran/$1');
$routes->post('admin/pembayaran/hapus-semua', 'Admin::hapusSemuaPembayaran');

$routes->get('admin/laporan-pembayaran', 'Admin::laporanPembayaran');
$routes->get('admin/durasi-sewa', 'Admin::durasiSewa');
$routes->post('admin/pembayaran/aktifkan-timer/(:num)', 'Admin::aktifkanTimer/$1');

// Pembayaran
$routes->get('pembayaran/(:num)', 'Pembayaran::index/$1');
$routes->post('pembayaran/proses', 'Pembayaran::proses');
$routes->get('pembayaran/sukses', 'Pembayaran::sukses');
$routes->get('pembayaran/invoice/(:num)', 'Pembayaran::invoice/$1');
// Webhook Pakasir - menerima POST dari Pakasir, GET untuk testing di development
$routes->match(['get', 'post'], 'pembayaran/webhook', 'Pembayaran::webhook');
// Pembayaran Langsung tanpa penawaran
$routes->get('pembayaran/alat/(:num)', 'Pembayaran::langsung/$1');
$routes->get('pembayaran/langsung', 'Pembayaran::langsung');
$routes->get('pembayaran/langsung/(:num)', 'Pembayaran::langsung/$1');
$routes->post('pembayaran/langsung', 'Pembayaran::prosesLangsung');
