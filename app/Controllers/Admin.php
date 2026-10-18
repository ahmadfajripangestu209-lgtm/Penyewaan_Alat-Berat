<?php
namespace App\Controllers;

use App\Models\AlatBeratModel;
use App\Models\AlatBeratGambarModel;

class Admin extends BaseController
{
    public function pembayaran()
    {
        if (!session('logged_in') || session('user_role') !== 'admin') {
            return redirect()->to('/login')->with('error', 'Akses hanya untuk admin!');
        }
        $model = new \App\Models\PembayaranModel();
        $list = [];
        try {
            $list = $model->orderBy('created_at', 'DESC')->findAll();
        } catch (\Throwable $e) {
            $list = [];
        }
        $data = ['pembayaran' => $list];
        return view('admin/pembayaran', $data);
    }
    public function index()
    {
        if (!session('logged_in') || session('user_role') !== 'admin') {
            return redirect()->to('/login')->with('error', 'Akses hanya untuk admin!');
        }
        $userModel = new \App\Models\UserModel();
        $alatBeratModel = new \App\Models\AlatBeratModel();
        $kuotasiModel = new \App\Models\RiwayatPenawaranModel();
        $totalBayar = 0;
        $totalBayarPending = 0;
        $totalBayarRejected = 0;
        $activeSewaCount = 0;
        $nextRentalEndAt = null;
        $nextRentalAlat = null;
        $laporanMingguan = [
            'total_pendapatan' => 0,
        ];
        $laporanBulanan = [
            'total_pendapatan' => 0,
        ];
        try {
            $db = \Config\Database::connect();
            if ($db->tableExists('pembayaran')) {
                $sum = $db->table('pembayaran')->selectSum('jumlah')->where('status', 'verified')->get()->getRowArray();
                $totalBayar = (int)($sum['jumlah'] ?? 0);
                $sumP = $db->table('pembayaran')->selectSum('jumlah')->where('status', 'pending')->get()->getRowArray();
                $totalBayarPending = (int)($sumP['jumlah'] ?? 0);
                $sumR = $db->table('pembayaran')->selectSum('jumlah')->where('status', 'rejected')->get()->getRowArray();
                $totalBayarRejected = (int)($sumR['jumlah'] ?? 0);
                $today = date('Y-m-d');
                $weekStart = date('Y-m-d', strtotime('-6 days'));
                $monthStart = date('Y-m-01');
                $monthEnd = date('Y-m-t');
                $sumWeek = $db->table('pembayaran')
                    ->selectSum('jumlah')
                    ->where('status', 'verified')
                    ->where('created_at >=', $weekStart . ' 00:00:00')
                    ->where('created_at <=', $today . ' 23:59:59')
                    ->get()
                    ->getRowArray();
                $laporanMingguan['total_pendapatan'] = (int)($sumWeek['jumlah'] ?? 0);
                $sumMonth = $db->table('pembayaran')
                    ->selectSum('jumlah')
                    ->where('status', 'verified')
                    ->where('created_at >=', $monthStart . ' 00:00:00')
                    ->where('created_at <=', $monthEnd . ' 23:59:59')
                    ->get()
                    ->getRowArray();
                $laporanBulanan['total_pendapatan'] = (int)($sumMonth['jumlah'] ?? 0);

                $activeSewaCount = (int)$db->table('pembayaran')
                    ->where('status', 'verified')
                    ->where('rental_end_at IS NOT NULL', null, false)
                    ->where('rental_end_at >', date('Y-m-d H:i:s'))
                    ->countAllResults();

                $nextRental = $db->table('pembayaran')
                    ->select('rental_end_at, nama_alat')
                    ->where('status', 'verified')
                    ->where('rental_end_at IS NOT NULL', null, false)
                    ->where('rental_end_at >', date('Y-m-d H:i:s'))
                    ->orderBy('rental_end_at', 'ASC')
                    ->get()
                    ->getRowArray();
                if (!empty($nextRental)) {
                    $nextRentalEndAt = $nextRental['rental_end_at'];
                    $nextRentalAlat = $nextRental['nama_alat'] ?? null;
                }
            }
        } catch (\Throwable $e) {
            $totalBayar = 0;
            $totalBayarPending = 0;
            $totalBayarRejected = 0;
        }

        $data = [
            'total_user' => $userModel->countAllResults(),
            'total_alat' => $alatBeratModel->countAllResults(),
            'total_kuotasi' => $kuotasiModel->where('harga_ditawarkan IS NOT NULL', null, false)->countAllResults(),
            'total_bayar' => $totalBayar,
            'total_bayar_pending' => $totalBayarPending,
            'total_bayar_rejected' => $totalBayarRejected,
            'active_sewa_count' => $activeSewaCount,
            'next_rental_end_at' => $nextRentalEndAt,
            'next_rental_alat' => $nextRentalAlat,
            'laporan_mingguan' => $laporanMingguan,
            'laporan_bulanan' => $laporanBulanan,
        ];
        return view('admin/index', $data);
    }

    public function laporanPembayaran()
    {
        if (!session('logged_in') || session('user_role') !== 'admin') {
            return redirect()->to('/login')->with('error', 'Akses hanya untuk admin!');
        }
        $weekStartParam = $this->request->getGet('week_start');
        $monthParam = $this->request->getGet('month');
        if ($weekStartParam) {
            $weekStart = $weekStartParam;
            $today = date('Y-m-d', strtotime($weekStartParam . ' +6 days'));
        } else {
            $today = date('Y-m-d');
            $weekStart = date('Y-m-d', strtotime('-6 days'));
        }
        if ($monthParam) {
            $monthStart = $monthParam . '-01';
            $monthEnd = date('Y-m-t', strtotime($monthStart));
        } else {
            $monthStart = date('Y-m-01');
            $monthEnd = date('Y-m-t');
            $monthParam = date('Y-m');
        }
        $namaUsaha = 'EDI JAYA JAMBI';
        try {
            $profil = require(APPPATH . 'Config/profil_perusahaan.php');
            if (!empty($profil['hero_title'])) {
                $namaUsaha = strtoupper($profil['hero_title']);
            }
        } catch (\Throwable $e) {}
        $laporanMingguan = [
            'periode_mulai' => null,
            'periode_selesai' => null,
            'transaksi' => [],
            'total_transaksi' => 0,
            'total_pendapatan' => 0,
            'total_denda' => 0,
            'total_bersih' => 0,
        ];
        $laporanBulanan = [
            'periode_mulai' => null,
            'periode_selesai' => null,
            'transaksi' => [],
            'total_transaksi' => 0,
            'total_pendapatan' => 0,
            'total_denda' => 0,
            'total_bersih' => 0,
        ];
        try {
            $db = \Config\Database::connect();
            if ($db->tableExists('pembayaran')) {
                $builderWeek = $db->table('pembayaran p');
                $builderWeek->select('p.*, rp.lama_sewa, ab.jenis AS jenis_alat');
                $builderWeek->join('riwayat_penawaran rp', 'rp.id = p.riwayat_penawaran_id', 'left');
                $builderWeek->join('alat_berat ab', 'ab.id = rp.alat_berat_id', 'left');
                $builderWeek->where('p.status', 'verified');
                $builderWeek->where('p.created_at >=', $weekStart . ' 00:00:00');
                $builderWeek->where('p.created_at <=', $today . ' 23:59:59');
                $builderWeek->orderBy('p.created_at', 'ASC');
                $rowsWeek = $builderWeek->get()->getResultArray();
                $totalPendapatanWeek = 0;
                foreach ($rowsWeek as $r) {
                    $totalPendapatanWeek += (int)($r['jumlah'] ?? 0);
                }
                $laporanMingguan = [
                    'periode_mulai' => $weekStart,
                    'periode_selesai' => $today,
                    'transaksi' => $rowsWeek,
                    'total_transaksi' => count($rowsWeek),
                    'total_pendapatan' => $totalPendapatanWeek,
                    'total_denda' => 0,
                    'total_bersih' => $totalPendapatanWeek,
                ];
                $builderMonth = $db->table('pembayaran p');
                $builderMonth->select('p.*, rp.lama_sewa, ab.jenis AS jenis_alat');
                $builderMonth->join('riwayat_penawaran rp', 'rp.id = p.riwayat_penawaran_id', 'left');
                $builderMonth->join('alat_berat ab', 'ab.id = rp.alat_berat_id', 'left');
                $builderMonth->where('p.status', 'verified');
                $builderMonth->where('p.created_at >=', $monthStart . ' 00:00:00');
                $builderMonth->where('p.created_at <=', $monthEnd . ' 23:59:59');
                $builderMonth->orderBy('p.created_at', 'ASC');
                $rowsMonth = $builderMonth->get()->getResultArray();
                $totalPendapatanMonth = 0;
                foreach ($rowsMonth as $r) {
                    $totalPendapatanMonth += (int)($r['jumlah'] ?? 0);
                }
                $laporanBulanan = [
                    'periode_mulai' => $monthStart,
                    'periode_selesai' => $monthEnd,
                    'transaksi' => $rowsMonth,
                    'total_transaksi' => count($rowsMonth),
                    'total_pendapatan' => $totalPendapatanMonth,
                    'total_denda' => 0,
                    'total_bersih' => $totalPendapatanMonth,
                ];
            }
        } catch (\Throwable $e) {}
        $data = [
            'nama_usaha' => $namaUsaha,
            'laporan_mingguan' => $laporanMingguan,
            'laporan_bulanan' => $laporanBulanan,
            'filter_week_start' => $weekStart,
            'filter_month' => $monthParam,
        ];
        return view('admin/laporan_pembayaran', $data);
    }

    public function durasiSewa()
    {
        if (!session('logged_in') || session('user_role') !== 'admin') {
            return redirect()->to('/login')->with('error', 'Akses hanya untuk admin!');
        }

        $filter = $this->request->getGet('filter') ?? 'all';
        $pembayaranModel = new \App\Models\PembayaranModel();
        $builder = $pembayaranModel->builder();
        $builder->select('p.*, rp.lama_sewa, ab.jenis AS jenis_alat, ab.id AS alat_id, ab.status AS alat_status, ab.nama AS alat_nama');
        $builder->from('pembayaran p');
        $builder->join('riwayat_penawaran rp', 'rp.id = p.riwayat_penawaran_id', 'left');
        $builder->join('alat_berat ab', 'ab.id = rp.alat_berat_id', 'left');
        $builder->where('p.status', 'verified');

        if ($filter === 'aktif' || $filter === 'belum') {
            $builder->where('(p.show_in_durasi IS NULL OR p.show_in_durasi = 1)', null, false);
        }

        if ($filter === 'aktif') {
            $builder->where('p.rental_end_at IS NOT NULL', null, false);
            $builder->where('p.rental_end_at >', date('Y-m-d H:i:s'));
        } elseif ($filter === 'selesai') {
            $builder->where('p.rental_end_at IS NOT NULL', null, false);
            $builder->where('p.rental_end_at <=', date('Y-m-d H:i:s'));
        } elseif ($filter === 'belum') {
            $builder->where('p.rental_end_at IS NULL', null, false);
        }

        $builder->orderBy('p.created_at', 'DESC');
        $pembayaran = $builder->get()->getResultArray();

        // Dedupe cards by riwayat_penawaran_id so duplicate verified payments for the same rental only show once.
        $uniquePembayaran = [];
        foreach ($pembayaran as $row) {
            $key = !empty($row['riwayat_penawaran_id']) ? $row['riwayat_penawaran_id'] : 'p_' . $row['id'];
            if (!isset($uniquePembayaran[$key])) {
                $uniquePembayaran[$key] = $row;
            }
        }
        $pembayaran = array_values($uniquePembayaran);

        return view('admin/durasi_sewa', [
            'pembayaran' => $pembayaran,
            'filter' => $filter,
        ]);
    }

    public function aktifkanTimer($id)
    {
        if (!session('logged_in') || session('user_role') !== 'admin') {
            return $this->response->setJSON(['success' => false, 'message' => 'Akses hanya untuk admin'])->setStatusCode(403);
        }

        $pembayaranModel = new \App\Models\PembayaranModel();
        $pembayaran = $pembayaranModel->find($id);
        if (!$pembayaran || ($pembayaran['status'] ?? '') !== 'verified') {
            return $this->response->setJSON(['success' => false, 'message' => 'Pembayaran tidak ditemukan atau belum terverifikasi'])->setStatusCode(404);
        }

        if (!empty($pembayaran['rental_end_at'])) {
            $timestamp = strtotime($pembayaran['rental_end_at']);
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Timer sudah aktif',
                'rental_end_at' => $pembayaran['rental_end_at'],
                'rental_end_at_unix' => $timestamp
            ]);
        }

        $riwayatModel = new \App\Models\RiwayatPenawaranModel();
        $riwayat = $riwayatModel->find($pembayaran['riwayat_penawaran_id']);
        $lama = !empty($riwayat['lama_sewa']) && is_numeric($riwayat['lama_sewa']) ? (int)$riwayat['lama_sewa'] : 0;
        if ($lama <= 0) {
            return $this->response->setJSON(['success' => false, 'message' => 'Lama sewa tidak valid'])->setStatusCode(400);
        }

        $dt = new \DateTime('now', new \DateTimeZone('Asia/Jakarta'));
        $dt->modify('+' . $lama . ' hours');
        $endAt = $dt->format('Y-m-d H:i:s');

        $pembayaranModel->update($id, ['rental_end_at' => $endAt]);

        $userId = $pembayaran['user_id'] ?? null;
        if (!empty($userId)) {
            $alatName = $pembayaran['nama_alat'] ?? null;
            if (empty($alatName) && !empty($pembayaran['riwayat_penawaran_id'])) {
                $db2 = \Config\Database::connect();
                $row = $db2->table('riwayat_penawaran rp')
                    ->select('ab.nama AS alat_nama')
                    ->join('alat_berat ab', 'ab.id = rp.alat_berat_id', 'left')
                    ->where('rp.id', $pembayaran['riwayat_penawaran_id'])
                    ->get()
                    ->getRowArray();
                $alatName = $row['alat_nama'] ?? $alatName;
            }
            $namaAlat = $alatName ?? 'Alat Berat';
            $pesan = 'Notifikasi: Durasi sewa untuk <b>' . esc($namaAlat) . '</b> telah dimulai. Sewa akan berakhir pada <b>' . esc($endAt) . '</b>.';
            $link = base_url('alat-berat/riwayat-penawaran');
            $notifikasiModel = new \App\Models\NotifikasiModel();
            $notifikasiModel->insert([
                'user_id' => $userId,
                'pesan' => $pesan,
                'link' => $link,
                'tipe' => 'pesan',
                'is_read' => 0,
            ]);
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Timer berhasil dijalankan',
            'rental_end_at' => $endAt,
            'rental_end_at_unix' => strtotime($endAt),
        ]);
    }

    public function users()
    {
        if (!session('logged_in') || session('user_role') !== 'admin') {
            return redirect()->to('/login')->with('error', 'Akses hanya untuk admin!');
        }
        $userModel = new \App\Models\UserModel();
        $data = [
            'users' => $userModel->findAll()
        ];
        return view('admin/users', $data);
    }

    public function alatBerat()
    {
        if (!session('logged_in') || session('user_role') !== 'admin') {
            return redirect()->to('/login')->with('error', 'Akses hanya untuk admin!');
        }
        $alatBeratModel = new AlatBeratModel();
        $alat_berat = $alatBeratModel->findAll();
        // Urutan alat berat custom
        $order = [
            'Excavator',
            'Vibro Roller',
            'Dozer',
            'Motor Grader',
            'Wheel Loader',
            'Backhoe loader',
            'Dump truck',
            'Dump Truck Fuso',
        ];
        usort($alat_berat, function($a, $b) use ($order) {
            $idxA = array_search(ucwords(strtolower($a['jenis'])), $order);
            $idxB = array_search(ucwords(strtolower($b['jenis'])), $order);
            $idxA = $idxA === false ? 999 : $idxA;
            $idxB = $idxB === false ? 999 : $idxB;
            return $idxA <=> $idxB;
        });
        $data = [
            'alat_berat' => $alat_berat
        ];
        return view('admin/alat_berat', $data);
    }

    public function tambahAlatBerat()
    {
        if (!session('logged_in') || session('user_role') !== 'admin') {
            return redirect()->to('/login')->with('error', 'Akses hanya untuk admin!');
        }
        return view('admin/tambah_alat_berat');
    }

    public function simpanAlatBerat()
    {
        if (!session('logged_in') || session('user_role') !== 'admin') {
            return redirect()->to('/login')->with('error', 'Akses hanya untuk admin!');
        }
        $rules = [
            'nama' => 'required',
            'jenis' => 'required',
            'kapasitas' => 'required|numeric',
            'status' => 'required|in_list[tersedia,dipinjam,rusak]'
        ];
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        $gambarName = null;
        $gambar = $this->request->getFile('foto');
        if ($gambar && $gambar->isValid() && !$gambar->hasMoved()) {
            $gambarName = $gambar->getRandomName();
            $gambar->move('images/alat_berat', $gambarName);
        }
        $alatBeratModel = new \App\Models\AlatBeratModel();
        // Pastikan kolom min_harga_penawaran tersedia
        try {
            $db = \Config\Database::connect();
            $fields = $db->getFieldNames('alat_berat');
            if (!in_array('min_harga_penawaran', $fields)) {
                $forge = \Config\Database::forge();
                $forge->addColumn('alat_berat', [
                    'min_harga_penawaran' => ['type' => 'INT', 'constraint' => 11, 'null' => true, 'after' => 'harga_sewa'],
                ]);
            }
        } catch (\Throwable $e) {}
        // Normalisasi jenis alat berat
        $jenis_input = $this->request->getPost('jenis');
        $jenis_normal = $jenis_input;
        $map_jenis = [
            'Wheel Loader' => ['wheel loader', 'wheel-loader', 'Wheel Loader'],
            'Backhoe Loader' => ['backhoe loader', 'backhoe-loader', 'Backhoe Loader'],
            'Dump Truck' => ['dump truck', 'dump-truck', 'Dump Truck'],
            'Dump Truck Fuso' => ['dump truck fuso', 'dump-truck-fuso', 'Dump Truck Fuso'],
            'Vibro Roller' => ['vibro roller', 'vibro-roller', 'Vibro Roller'],
            'Motor Grader' => ['motor grader', 'motor-grader', 'Motor Grader'],
            'Dozer' => ['dozer', 'Dozer'],
            'Excavator' => ['excavator', 'Excavator'],
        ];
        foreach ($map_jenis as $baku => $variants) {
            foreach ($variants as $v) {
                if (strtolower(str_replace(['-', ' '], '', $jenis_input)) === strtolower(str_replace(['-', ' '], '', $v))) {
                    $jenis_normal = $baku;
                    break 2;
                }
            }
        }
        $alatBeratModel->save([
            'nama' => $this->request->getPost('nama'),
            'tipe' => $this->request->getPost('tipe'),
            'jenis' => $jenis_normal,
            'kapasitas' => $this->request->getPost('kapasitas'),
            'status' => $this->request->getPost('status'),
            'deskripsi' => $this->request->getPost('deskripsi'),
            'gambar' => $gambarName,
            'kategori' => $jenis_normal,
            'harga_sewa' => $this->request->getPost('harga_sewa'),
            'min_harga_penawaran' => $this->request->getPost('min_harga_penawaran'),
            'operator' => $this->request->getPost('operator'),
            'berat_operasional' => $this->request->getPost('berat_operasional'),
            'kapasitas_bucket' => $this->request->getPost('kapasitas_bucket'),
            'kedalaman_gali_maksimum' => $this->request->getPost('kedalaman_gali_maksimum'),
            'tinggi_transportasi' => $this->request->getPost('tinggi_transportasi'),
            'panjang_transportasi' => $this->request->getPost('panjang_transportasi'),
            'lebar_transportasi' => $this->request->getPost('lebar_transportasi'),
            'merek' => $this->request->getPost('merek'),
        ]);
        return redirect()->to('/admin/alat-berat')->with('message', 'Data alat berat berhasil ditambahkan!');
    }

    public function editAlatBerat($id)
    {
        if (!session('logged_in') || session('user_role') !== 'admin') {
            return redirect()->to('/login')->with('error', 'Akses hanya untuk admin!');
        }
        $alatBeratModel = new \App\Models\AlatBeratModel();
        $alat = $alatBeratModel->find($id);
        if (!$alat) {
            return redirect()->to('/admin/alat-berat')->with('error', 'Data tidak ditemukan!');
        }
        return view('admin/edit_alat_berat', ['alat' => $alat]);
    }

    public function updateAlatBerat($id)
    {
        if (!session('logged_in') || session('user_role') !== 'admin') {
            return redirect()->to('/login')->with('error', 'Akses hanya untuk admin!');
        }
        $rules = [
            'nama' => 'required',
            'jenis' => 'required',
            'kapasitas' => 'required|numeric',
            'status' => 'required|in_list[tersedia,dipinjam,rusak]'
        ];
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        $alatBeratModel = new \App\Models\AlatBeratModel();
        $alat = $alatBeratModel->find($id);
        $gambarName = $alat['gambar'];
        $gambar = $this->request->getFile('foto');
        if ($gambar && $gambar->isValid() && !$gambar->hasMoved()) {
            // Hapus gambar lama jika ada
            if ($gambarName && file_exists('images/alat_berat/'.$gambarName)) {
                unlink('images/alat_berat/'.$gambarName);
            }
            $gambarName = $gambar->getRandomName();
            $gambar->move('images/alat_berat', $gambarName);
        }
        // Normalisasi jenis alat berat
        $jenis_input = $this->request->getPost('jenis');
        $jenis_normal = $jenis_input;
        $map_jenis = [
            'Wheel Loader' => ['wheel loader', 'wheel-loader', 'Wheel Loader'],
            'Backhoe Loader' => ['backhoe loader', 'backhoe-loader', 'Backhoe Loader'],
            'Dump Truck' => ['dump truck', 'dump-truck', 'Dump Truck'],
            'Dump Truck Fuso' => ['dump truck fuso', 'dump-truck-fuso', 'Dump Truck Fuso'],
            'Vibro Roller' => ['vibro roller', 'vibro-roller', 'Vibro Roller'],
            'Motor Grader' => ['motor grader', 'motor-grader', 'Motor Grader'],
            'Dozer' => ['dozer', 'Dozer'],
            'Excavator' => ['excavator', 'Excavator'],
        ];
        foreach ($map_jenis as $baku => $variants) {
            foreach ($variants as $v) {
                if (strtolower(str_replace(['-', ' '], '', $jenis_input)) === strtolower(str_replace(['-', ' '], '', $v))) {
                    $jenis_normal = $baku;
                    break 2;
                }
            }
        }
        $alatBeratModel->update($id, [
            'nama' => $this->request->getPost('nama'),
            'tipe' => $this->request->getPost('tipe'),
            'jenis' => $jenis_normal,
            'kapasitas' => $this->request->getPost('kapasitas'),
            'status' => $this->request->getPost('status'),
            'deskripsi' => $this->request->getPost('deskripsi'),
            'gambar' => $gambarName,
            'kategori' => $jenis_normal,
            'harga_sewa' => $this->request->getPost('harga_sewa'),
            'min_harga_penawaran' => $this->request->getPost('min_harga_penawaran'),
            'operator' => $this->request->getPost('operator'),
            'berat_operasional' => $this->request->getPost('berat_operasional'),
            'kapasitas_bucket' => $this->request->getPost('kapasitas_bucket'),
            'kedalaman_gali_maksimum' => $this->request->getPost('kedalaman_gali_maksimum'),
            'tinggi_transportasi' => $this->request->getPost('tinggi_transportasi'),
            'panjang_transportasi' => $this->request->getPost('panjang_transportasi'),
            'lebar_transportasi' => $this->request->getPost('lebar_transportasi'),
            'merek' => $this->request->getPost('merek'),
        ]);
        return redirect()->to('/admin/alat-berat')->with('message', 'Data alat berat berhasil diupdate!');
    }

    public function hapusAlatBerat($id)
    {
        if (!session('logged_in') || session('user_role') !== 'admin') {
            return redirect()->to('/login')->with('error', 'Akses hanya untuk admin!');
        }
        $alatBeratModel = new \App\Models\AlatBeratModel();
        $alat = $alatBeratModel->find($id);
        if ($alat && !empty($alat['gambar']) && file_exists('images/alat_berat/'.$alat['gambar'])) {
            unlink('images/alat_berat/'.$alat['gambar']);
        }
        $alatBeratModel->delete($id);
        return redirect()->to('/admin/alat-berat')->with('message', 'Data alat berat berhasil dihapus!');
    }

    // Upload gambar alat berat (multiple)
    public function uploadGambarAlatBerat($id)
    {
        if (!session('logged_in') || session('user_role') !== 'admin') {
            return redirect()->to('/login')->with('error', 'Akses hanya untuk admin!');
        }
        $gambarModel = new AlatBeratGambarModel();
        $files = $this->request->getFiles();
        $errors = [];
        if (isset($files['gambar'])) {
            foreach ($files['gambar'] as $file) {
                if ($file->isValid() && !$file->hasMoved()) {
                    $newName = $file->getRandomName();
                    if (!$file->move('public/uploads', $newName)) {
                        $errors[] = $file->getErrorString();
                        continue;
                    }
                    $gambarModel->insert([
                        'alat_berat_id' => $id,
                        'filename' => $newName
                    ]);
                } else {
                    $errors[] = $file->getErrorString();
                }
            }
        }
        if (!empty($errors)) {
            return redirect()->back()->with('errors', $errors);
        }
        return redirect()->back()->with('message', 'Gambar berhasil diupload!');
    }

    // Hapus gambar alat berat
    public function hapusGambarAlatBerat($gambar_id)
    {
        if (!session('logged_in') || session('user_role') !== 'admin') {
            return redirect()->to('/login')->with('error', 'Akses hanya untuk admin!');
        }
        $gambarModel = new AlatBeratGambarModel();
        $gambar = $gambarModel->find($gambar_id);
        if ($gambar) {
            $filePath = 'public/uploads/' . $gambar['filename'];
            if (file_exists($filePath)) {
                unlink($filePath);
            }
            $gambarModel->delete($gambar_id);
        }
        return redirect()->back()->with('message', 'Gambar berhasil dihapus!');
    }

    public function pengaturan()
    {
        if (!session('logged_in') || session('user_role') !== 'admin') {
            return redirect()->to('/login')->with('error', 'Akses hanya untuk admin!');
        }
        $profil = require(APPPATH . 'Config/profil_perusahaan.php');
        return view('admin/pengaturan', [
            'foto' => $profil['foto'] ?? 'company-profile.jpg',
            'foto_header' => $profil['foto_header'] ?? 'default-header.jpg',
            'hero_title' => $profil['hero_title'] ?? 'Edi Jaya Jambi',
            'hero_subtitle' => $profil['hero_subtitle'] ?? 'Penyewaan Alat Berat Terpercaya',
            'hero_desc' => $profil['hero_desc'] ?? 'Solusi terbaik untuk kebutuhan alat berat Anda. Kami menyediakan berbagai jenis alat berat dengan kualitas terjamin dan harga kompetitif.',
        ]);
    }

    public function updateHero()
    {
        if (!session('logged_in') || session('user_role') !== 'admin') {
            return redirect()->to('/login')->with('error', 'Akses hanya untuk admin!');
        }
        $profil = require(APPPATH . 'Config/profil_perusahaan.php');
        $foto = $profil['foto'] ?? 'company-profile.jpg';
        $foto_header = $profil['foto_header'] ?? 'default-header.jpg';
        $hero_title = $this->request->getPost('hero_title');
        $hero_subtitle = $this->request->getPost('hero_subtitle');
        $hero_desc = $this->request->getPost('hero_desc');
        $configPath = APPPATH . 'Config/profil_perusahaan.php';
        $configContent = "<?php\nreturn [\n    'foto' => '" . addslashes($foto) . "',\n    'foto_header' => '" . addslashes($foto_header) . "',\n    'hero_title' => '" . addslashes($hero_title) . "',\n    'hero_subtitle' => '" . addslashes($hero_subtitle) . "',\n    'hero_desc' => '" . addslashes($hero_desc) . "',\n];\n";
        file_put_contents($configPath, $configContent);
        return redirect()->back()->with('message', 'Teks homepage berhasil diupdate!');
    }

    public function uploadFotoProfil()
    {
        if (!session('logged_in') || session('user_role') !== 'admin') {
            return redirect()->to('/login')->with('error', 'Akses hanya untuk admin!');
        }
        $foto = $this->request->getFile('foto');
        if ($foto && $foto->isValid() && !$foto->hasMoved()) {
            $newName = 'company-profile-' . time() . '.' . $foto->getExtension();
            $foto->move('public/images', $newName);
            // Update config
            $configPath = APPPATH . 'Config/profil_perusahaan.php';
            $configContent = "<?php\nreturn [\n    'foto' => '" . $newName . "',\n];\n";
            file_put_contents($configPath, $configContent);
            return redirect()->back()->with('message', 'Foto profil perusahaan berhasil diupdate!');
        }
        return redirect()->back()->with('error', 'Gagal upload foto.');
    }

    public function uploadFotoHeader()
    {
        if (!session('logged_in') || session('user_role') !== 'admin') {
            return redirect()->to('/login')->with('error', 'Akses hanya untuk admin!');
        }
        $foto = $this->request->getFile('foto_header');
        if ($foto && $foto->isValid() && !$foto->hasMoved()) {
            $newName = 'header-' . time() . '.' . $foto->getExtension();
            $foto->move('public/images', $newName);
            // Update config
            $profil = require(APPPATH . 'Config/profil_perusahaan.php');
            $configPath = APPPATH . 'Config/profil_perusahaan.php';
            $configContent = "<?php\nreturn [\n    'foto' => '" . ($profil['foto'] ?? 'company-profile.jpg') . "',\n    'foto_header' => '" . $newName . "',\n];\n";
            file_put_contents($configPath, $configContent);
            return redirect()->back()->with('message', 'Gambar header homepage berhasil diupdate!');
        }
        return redirect()->back()->with('error', 'Gagal upload gambar header.');
    }

    public function tambahUser()
    {
        if (!session('logged_in') || session('user_role') !== 'admin') {
            return redirect()->to('/login')->with('error', 'Akses hanya untuk admin!');
        }
        return view('admin/tambah_user');
    }

    public function simpanUser()
    {
        if (!session('logged_in') || session('user_role') !== 'admin') {
            return redirect()->to('/login')->with('error', 'Akses hanya untuk admin!');
        }
        $rules = [
            'name' => 'required',
            'email' => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[6]',
            'role' => 'required|in_list[user,admin]'
        ];
        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        $userModel = new \App\Models\UserModel();
        $userModel->save([
            'name' => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'role' => $this->request->getPost('role'),
        ]);
        return redirect()->to('/admin/users')->with('message', 'User berhasil ditambahkan!');
    }

    public function editUser($id)
    {
        if (!session('logged_in') || session('user_role') !== 'admin') {
            return redirect()->to('/login')->with('error', 'Akses hanya untuk admin!');
        }
        $userModel = new \App\Models\UserModel();
        $user = $userModel->find($id);
        if (!$user) {
            return redirect()->to('/admin/users')->with('error', 'User tidak ditemukan!');
        }
        return view('admin/edit_user', ['user' => $user]);
    }

    public function updateUser($id)
    {
        if (!session('logged_in') || session('user_role') !== 'admin') {
            return redirect()->to('/login')->with('error', 'Akses hanya untuk admin!');
        }
        $userModel = new \App\Models\UserModel();
        $user = $userModel->find($id);
        if (!$user) {
            return redirect()->to('/admin/users')->with('error', 'User tidak ditemukan!');
        }
        $rules = [
            'name' => 'required',
            'email' => 'required|valid_email|is_unique[users.email,id,{id}]',
            'role' => 'required|in_list[user,admin]'
        ];
        $data = [
            'name' => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
            'role' => $this->request->getPost('role'),
        ];
        if ($this->request->getPost('password')) {
            $data['password'] = password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);
            $rules['password'] = 'permit_empty|min_length[6]';
        }
        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        $userModel->update($id, $data);
        return redirect()->to('/admin/users')->with('message', 'User berhasil diupdate!');
    }

    public function hapusUser($id)
    {
        if (!session('logged_in') || session('user_role') !== 'admin') {
            return redirect()->to('/login')->with('error', 'Akses hanya untuk admin!');
        }
        $userModel = new \App\Models\UserModel();
        $userModel->delete($id);
        return redirect()->to('/admin/users')->with('message', 'User berhasil dihapus!');
    }

    public function kelolaKuotasi()
    {
        if (!session('logged_in') || session('user_role') !== 'admin') {
            return redirect()->to('/login')->with('error', 'Akses hanya untuk admin!');
        }
        $riwayatModel = new \App\Models\RiwayatPenawaranModel();
        $alatModel = new AlatBeratModel();
        $userModel = new \App\Models\UserModel();
        // Tampilkan hanya penawaran yang diajukan oleh user, bukan riwayat pembayaran langsung.
        $riwayat = $riwayatModel
            ->where('harga_ditawarkan IS NOT NULL', null, false)
            ->orderBy('tanggal_minta', 'DESC')
            ->findAll();
        foreach ($riwayat as &$r) {
            $alat = $alatModel->find($r['alat_berat_id']);
            $r['nama_alat'] = $alat ? $alat['nama'] : '-';
            // Tambah nama lengkap alat berat (nama + merek + kapasitas)
            if ($alat) {
                $namaLengkap = $alat['nama'];
                if (!empty($alat['merek'])) {
                    $namaLengkap .= ' ' . $alat['merek'];
                }
                if (!empty($alat['kapasitas'])) {
                    $namaLengkap .= ' ' . $alat['kapasitas'] . ' ton';
                }
                $r['nama_lengkap_alat'] = $namaLengkap;
            } else {
                $r['nama_lengkap_alat'] = '-';
            }
            if (!empty($r['user_id'])) {
                $user = $userModel->find($r['user_id']);
                $r['user_nama'] = $user['name'] ?? '-';
                $r['user_email'] = $user['email'] ?? '-';
            }
            // Konversi status lama 'ditawarkan' ke 'diterima' untuk tampilan
            if ($r['status'] === 'ditawarkan') {
                $r['status'] = 'diterima';
            }
        }
        $data = [
            'title' => 'Kelola Penawaran',
            'riwayat' => $riwayat
        ];
        return view('admin/kelola_penawaran', $data);
    }

    public function hapusKuotasi($id)
    {
        if (!session('logged_in') || session('user_role') !== 'admin') {
            return redirect()->to('/login')->with('error', 'Akses hanya untuk admin!');
        }
        $riwayatModel = new \App\Models\RiwayatPenawaranModel();
        $riwayatModel->delete($id);
        return redirect()->to('/admin/penawaran')->with('message', 'Data kuotasi berhasil dihapus!');
    }

    public function updateStatusKuotasi($id)
    {
        if (!session('logged_in') || session('user_role') !== 'admin') {
            return redirect()->to('/login')->with('error', 'Akses hanya untuk admin!');
        }
        $status = $this->request->getPost('status');
        $pesanAdmin = $this->request->getPost('pesan_admin');
        if ($status === 'diterima') {
            $status = 'diterima';
        }
        $riwayatModel = new \App\Models\RiwayatPenawaranModel();
        $riwayat = $riwayatModel->find($id);
        $updateData = ['status' => $status];
        if ($pesanAdmin !== null) {
            $updateData['pesan_admin'] = $pesanAdmin;
        }
        $riwayatModel->update($id, $updateData);
        // Notifikasi ke user
        if ($riwayat && !empty($riwayat['user_id'])) {
            $notifikasiModel = new \App\Models\NotifikasiModel();
            $alatModel = new \App\Models\AlatBeratModel();
            $alat = $alatModel->find($riwayat['alat_berat_id']);
            $namaAlat = $alat ? $alat['nama'] : 'Alat Berat';
            $pesan = 'Status penawaran Anda untuk <b>' . esc($namaAlat) . '</b> telah diubah menjadi <b>' . ucfirst($status) .  '</b>';
            if ($pesanAdmin) {
                $pesan .= '<br><b>Catatan Admin:</b> ' . esc($pesanAdmin);
            }
            $link = base_url('alat-berat/riwayat-penawaran');
            $notifikasiModel->insert([
                'user_id' => $riwayat['user_id'],
                'pesan' => $pesan,
                'link' => $link,
                'tipe' => 'kuotasi',
                'is_read' => 0
            ]);
        }
        return redirect()->to('/admin/penawaran')->with('message', 'Status kuotasi berhasil diupdate!');
    }

    public function verifikasiPembayaran($id)
    {
        if (!session('logged_in') || session('user_role') !== 'admin') {
            return redirect()->to('/login')->with('error', 'Akses hanya untuk admin!');
        }
        $model = new \App\Models\PembayaranModel();
        $p = $model->find($id);
        $updateData = ['status' => 'verified'];
        // rental_end_at tidak disetel otomatis di sini.
        // Timer akan dimulai secara manual dari halaman Durasi Sewa oleh admin.
        $model->update($id, $updateData);
        try {
            $p = $model->find($id);
            if ($p) {
                $riwayatModel = new \App\Models\RiwayatPenawaranModel();
                $alatModel = new AlatBeratModel();
                $riwayat = !empty($p['riwayat_penawaran_id']) ? $riwayatModel->find($p['riwayat_penawaran_id']) : null;
                $alat = $riwayat ? $alatModel->find($riwayat['alat_berat_id']) : null;
                $namaAlat = $alat ? $alat['nama'] : ($p['nama_alat'] ?? 'Alat Berat');
                $userId = $p['user_id'] ?? ($riwayat['user_id'] ?? null);
                if (!empty($userId)) {
                    $notifikasiModel = new \App\Models\NotifikasiModel();
                    $pesan = 'Pembayaran Anda untuk <b>' . esc($namaAlat) . '</b> telah <b>terverifikasi</b>. Status: <b>Lunas</b>.';
                    $link = base_url('alat-berat/riwayat-penawaran');
                    $notifikasiModel->insert([
                        'user_id' => $userId,
                        'pesan' => $pesan,
                        'link' => $link,
                        'tipe' => 'kuotasi',
                        'is_read' => 0
                    ]);
                }
            }
        } catch (\Throwable $e) {
            // abaikan kesalahan notifikasi agar verifikasi tetap berjalan
        }
        return redirect()->to('/admin/pembayaran')->with('message', 'Pembayaran terverifikasi');
    }

    public function dipinjamPembayaran($id)
    {
        if (!session('logged_in') || session('user_role') !== 'admin') {
            return $this->response->setJSON(['success' => false, 'message' => 'Akses hanya untuk admin'])->setStatusCode(403);
        }

        $model = new \App\Models\PembayaranModel();
        $p = $model->find($id);
        if (!$p) {
            return $this->response->setJSON(['success' => false, 'message' => 'Pembayaran tidak ditemukan'])->setStatusCode(404);
        }

        $riwayatId = $p['riwayat_penawaran_id'] ?? null;
        if (empty($riwayatId)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Data riwayat tidak tersedia untuk pembayaran ini'])->setStatusCode(400);
        }

        $riwayatModel = new \App\Models\RiwayatPenawaranModel();
        $riwayat = $riwayatModel->find($riwayatId);
        if (!$riwayat || empty($riwayat['alat_berat_id'])) {
            return $this->response->setJSON(['success' => false, 'message' => 'Alat tidak ditemukan untuk riwayat ini'])->setStatusCode(404);
        }

        $alatId = (int)$riwayat['alat_berat_id'];
        $alatModel = new AlatBeratModel();
        try {
            $alatModel->update($alatId, ['status' => 'dipinjam']);
        } catch (\Throwable $e) {
            return $this->response->setJSON(['success' => false, 'message' => 'Gagal mengupdate status alat: ' . $e->getMessage()])->setStatusCode(500);
        }

        return $this->response->setJSON(['success' => true, 'message' => 'Status alat berhasil diubah menjadi Dipinjam']);
    }

    public function tersediaPembayaran($id)
    {
        if (!session('logged_in') || session('user_role') !== 'admin') {
            return $this->response->setJSON(['success' => false, 'message' => 'Akses hanya untuk admin'])->setStatusCode(403);
        }

        $model = new \App\Models\PembayaranModel();
        $p = $model->find($id);
        if (!$p) {
            return $this->response->setJSON(['success' => false, 'message' => 'Pembayaran tidak ditemukan'])->setStatusCode(404);
        }

        $riwayatId = $p['riwayat_penawaran_id'] ?? null;
        if (empty($riwayatId)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Data riwayat tidak tersedia untuk pembayaran ini'])->setStatusCode(400);
        }

        $riwayatModel = new \App\Models\RiwayatPenawaranModel();
        $riwayat = $riwayatModel->find($riwayatId);
        if (!$riwayat || empty($riwayat['alat_berat_id'])) {
            return $this->response->setJSON(['success' => false, 'message' => 'Alat tidak ditemukan untuk riwayat ini'])->setStatusCode(404);
        }

        $alatId = (int)$riwayat['alat_berat_id'];
        $alatModel = new AlatBeratModel();
        try {
            $alatModel->update($alatId, ['status' => 'tersedia']);
        } catch (\Throwable $e) {
            return $this->response->setJSON(['success' => false, 'message' => 'Gagal mengupdate status alat: ' . $e->getMessage()])->setStatusCode(500);
        }

        return $this->response->setJSON(['success' => true, 'message' => 'Status alat berhasil diubah menjadi Tersedia']);
    }

    public function tolakPembayaran($id)
    {
        if (!session('logged_in') || session('user_role') !== 'admin') {
            return redirect()->to('/login')->with('error', 'Akses hanya untuk admin!');
        }
        $model = new \App\Models\PembayaranModel();
        $alasan = $this->request->getPost('alasan_penolakan');
        try {
            $db = \Config\Database::connect();
            $fields = $db->getFieldNames('pembayaran');
            if (!in_array('alasan_penolakan', $fields)) {
                $forge = \Config\Database::forge();
                $forge->addColumn('pembayaran', [
                    'alasan_penolakan' => ['type' => 'TEXT', 'null' => true],
                ]);
            }
        } catch (\Throwable $e) {}
        $updateData = ['status' => 'rejected'];
        if ($alasan !== null) {
            $updateData['alasan_penolakan'] = $alasan;
        }
        $model->update($id, $updateData);
        try {
            $p = $model->find($id);
            if ($p) {
                $riwayatModel = new \App\Models\RiwayatPenawaranModel();
                $alatModel = new AlatBeratModel();
                $riwayat = !empty($p['riwayat_penawaran_id']) ? $riwayatModel->find($p['riwayat_penawaran_id']) : null;
                $alat = $riwayat ? $alatModel->find($riwayat['alat_berat_id']) : null;
                $namaAlat = $alat ? $alat['nama'] : ($p['nama_alat'] ?? 'Alat Berat');
                $userId = $p['user_id'] ?? ($riwayat['user_id'] ?? null);
                if (!empty($userId)) {
                    $notifikasiModel = new \App\Models\NotifikasiModel();
                    $pesan = 'Pembayaran Anda untuk <b>' . esc($namaAlat) . '</b> telah <b>ditolak</b>.';
                    if (!empty($alasan)) {
                        $pesan .= '<br><b>Alasan:</b> ' . esc($alasan);
                    } else if (!empty($p['alasan_penolakan'])) {
                        $pesan .= '<br><b>Alasan:</b> ' . esc($p['alasan_penolakan']);
                    }
                    $pesan .= '<br>Silakan periksa kembali bukti transfer atau hubungi admin.';
                    $link = base_url('alat-berat/riwayat-penawaran');
                    $notifikasiModel->insert([
                        'user_id' => $userId,
                        'pesan' => $pesan,
                        'link' => $link,
                        'tipe' => 'kuotasi',
                        'is_read' => 0
                    ]);
                }
            }
        } catch (\Throwable $e) {
            // abaikan kesalahan notifikasi agar proses tetap berjalan
        }
        return redirect()->to('/admin/pembayaran')->with('message', 'Pembayaran ditolak');
    }

    public function hapusPembayaran($id)
    {
        if (!session('logged_in') || session('user_role') !== 'admin') {
            return redirect()->to('/login')->with('error', 'Akses hanya untuk admin!');
        }
        $model = new \App\Models\PembayaranModel();
        $p = $model->find($id);
        if ($p) {
            if (!empty($p['bukti_transfer'])) {
                $filePath = 'public/uploads/bukti_transfer/' . $p['bukti_transfer'];
                if (file_exists($filePath)) {
                    @unlink($filePath);
                }
            }
            $model->delete($id);
        }

        $returnTo = $this->request->getPost('return_to');
        if (!empty($returnTo)) {
            $parsed = parse_url($returnTo);
            if (isset($parsed['host']) || (isset($parsed['path']) && strpos($parsed['path'], '/admin/') === 0)) {
                return redirect()->to($returnTo)->with('message', 'Pembayaran berhasil dihapus');
            }
        }

        return redirect()->to('/admin/pembayaran')->with('message', 'Pembayaran berhasil dihapus');
    }

    public function hapusSemuaPembayaran()
    {
        if (!session('logged_in') || session('user_role') !== 'admin') {
            return redirect()->to('/login')->with('error', 'Akses hanya untuk admin!');
        }
        $model = new \App\Models\PembayaranModel();
        $all = [];
        try {
            $all = $model->findAll();
        } catch (\Throwable $e) {}
        foreach ($all as $p) {
            if (!empty($p['bukti_transfer'])) {
                $filePath = 'public/uploads/bukti_transfer/' . $p['bukti_transfer'];
                if (file_exists($filePath)) {
                    @unlink($filePath);
                }
            }
        }
        try {
            $model->truncate();
        } catch (\Throwable $e) {
            // fallback: delete all rows
            $model->where('id >', 0)->delete();
        }
        return redirect()->to('/admin/pembayaran')->with('message', 'Semua data pembayaran berhasil dihapus');
    }

    public function hapusSemuaKuotasi()
    {
        if (!session('logged_in') || session('user_role') !== 'admin') {
            return redirect()->to('/login')->with('error', 'Akses hanya untuk admin!');
        }
        
        $riwayatModel = new \App\Models\RiwayatPenawaranModel();
        $riwayatModel->truncate(); // Hapus semua data dari tabel
        
        return redirect()->to('/admin/penawaran')->with('message', 'Semua riwayat kuotasi berhasil dihapus!');
    }

    public function hapusSemuaAlatBerat()
    {
        if (!session('logged_in') || session('user_role') !== 'admin') {
            return redirect()->to('/login')->with('error', 'Akses hanya untuk admin!');
        }
        $alatBeratModel = new AlatBeratModel();
        $alatBeratModel->where('id >', 0)->delete();
        return redirect()->to('/admin/alat-berat')->with('message', 'Semua data alat berat berhasil dihapus!');
    }
} 
