<?php

namespace App\Controllers;

use App\Models\AlatBeratModel;
use App\Models\AlatBeratGambarModel;
use App\Models\RiwayatPenawaranModel;
use App\Models\UserModel;
use App\Models\PembayaranModel;

class AlatBerat extends BaseController

{
    protected $alatBeratModel;

    public function __construct()
    {
        $this->alatBeratModel = new AlatBeratModel();
    }

    public function index()
    {
        $q = $this->request->getGet('q');
        $status = $this->request->getGet('status');
        $jenis = $this->request->getGet('jenis');
        $sort = $this->request->getGet('sort');
        $order = $this->request->getGet('order') ?: 'asc';
        $merek = $this->request->getGet('merek');
        $kapasitas = $this->request->getGet('kapasitas');
        $builder = $this->alatBeratModel;
        // Mapping jenis baku ke semua varian
        $jenis_variants = [
            'Wheel Loader' => ['Wheel Loader', 'wheel-loader', 'wheel loader'],
            'Backhoe Loader' => ['Backhoe Loader', 'Backhoe loader', 'backhoe-loader', 'backhoe loader'],
            'Dump Truck' => ['Dump Truck', 'Dump truck', 'dump-truck', 'dump truck'],
            'Dump Truck Fuso' => ['Dump Truck Fuso', 'dump-truck-fuso', 'dump truck fuso'],
            'Vibro Roller' => ['Vibro Roller', 'Vibro roller', 'vibro-roller', 'vibro roller'],
            'Motor Grader' => ['Motor Grader', 'Motor grader', 'motor-grader', 'motor grader'],
            'Dozer' => ['Dozer', 'dozer'],
            'Excavator' => ['Excavator', 'excavator'],
        ];
        if ($q) {
            $builder = $builder->groupStart()
                ->like('nama', $q)
                ->orLike('jenis', $q)
                ->orLike('deskripsi', $q)
                ->groupEnd();
        }
        if ($status) {
            $builder = $builder->where('status', $status);
        }
        if ($jenis) {
            $all_variants = [];
            if (is_array($jenis)) {
                foreach ($jenis as $j) {
                    if (isset($jenis_variants[$j])) {
                        $all_variants = array_merge($all_variants, $jenis_variants[$j]);
                    } else {
                        $all_variants[] = $j;
                    }
                }
            } else {
                if (isset($jenis_variants[$jenis])) {
                    $all_variants = $jenis_variants[$jenis];
                } else {
                    $all_variants[] = $jenis;
                }
            }
            $builder = $builder->whereIn('jenis', $all_variants);
        }
        if ($merek) {
            if (is_array($merek)) {
                $builder = $builder->whereIn('merek', $merek);
            } else {
                $builder = $builder->where('merek', $merek);
            }
        }
        if ($kapasitas) {
            if (is_array($kapasitas)) {
                $builder = $builder->groupStart();
                foreach ($kapasitas as $range) {
                    [$min, $max] = explode('-', $range);
                    $builder = $builder->orWhere('kapasitas >=', (float)$min)->where('kapasitas <=', (float)$max);
                }
                $builder = $builder->groupEnd();
            } else {
                [$min, $max] = explode('-', $kapasitas);
                $builder = $builder->where('kapasitas >=', (float)$min)->where('kapasitas <=', (float)$max);
            }
        }
        if ($sort && in_array($sort, ['harga_sewa','kapasitas','nama'])) {
            $builder = $builder->orderBy($sort, $order);
        }
        $data = [
            'title' => 'Daftar Alat Berat',
            'alat_berat' => $builder->findAll(),
            'filter_status' => $status,
            'filter_jenis' => $jenis,
            'filter_merek' => $merek,
            'filter_kapasitas' => $kapasitas,
            'sort' => $sort,
            'order' => $order
        ];
        $jenisList = $this->alatBeratModel->select('jenis')->distinct()->orderBy('jenis')->findAll();
        $merekList = $this->alatBeratModel->select('merek')->distinct()->orderBy('merek')->findAll();
        return view('alat_berat/cards', array_merge($data, [
            'jenisList' => array_filter(array_map(fn($j) => $j['jenis'], $jenisList)),
            'merekList' => array_filter(array_map(fn($m) => $m['merek'], $merekList)),
        ]));
    }

    public function create()
    {
        $data = [
            'title' => 'Tambah Alat Berat'
        ];

        return view('alat_berat/create', $data);
    }

    public function store()
    {
        $rules = [
            'nama' => 'required',
            'jenis' => 'required',
            'kapasitas' => 'required|numeric',
            'status' => 'required'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Normalisasi jenis alat berat
        $jenis_input = $this->request->getPost('jenis');
        $jenis_normal = $jenis_input;
        $map_jenis = [
            'wheel loader' => 'Wheel Loader',
            'wheel-loader' => 'Wheel Loader',
            'Wheel Loader' => 'Wheel Loader',
            // tambahkan mapping lain jika perlu
        ];
        $key = strtolower(str_replace(['-', ' '], '', $jenis_input));
        foreach ($map_jenis as $varian => $baku) {
            if ($key === strtolower(str_replace(['-', ' '], '', $varian))) {
                $jenis_normal = $baku;
                break;
            }
        }

        $this->alatBeratModel->insert([
            'nama' => $this->request->getPost('nama'),
            'jenis' => $jenis_normal,
            'kapasitas' => $this->request->getPost('kapasitas'),
            'status' => $this->request->getPost('status')
        ]);

        return redirect()->to('/alat-berat')->with('message', 'Data berhasil ditambahkan');
    }

    public function excavator()
    {
        $alatBeratModel = new \App\Models\AlatBeratModel();
        // Cari semua varian Excavator
        $alat_berat = $alatBeratModel->whereIn('jenis', ['Excavator', 'excavator'])->findAll();
        $data = [
            'title' => 'Excavator',
            'deskripsi' => 'Menggali, memindahkan material, konstruksi, demolisi — ini adalah alat yang Anda cari. Dikenal di Indonesia dengan berbagai nama panggilan: <i>bego</i>, <i>beko</i>, atau ekskavator.',
            'list' => $alat_berat
        ];
        return view('alat_berat/excavator', $data);
    }

    public function vibroRoller()
    {
        $alatBeratModel = new \App\Models\AlatBeratModel();
        // Cari semua varian Vibro Roller
        $alat_berat = $alatBeratModel->whereIn('jenis', ['Vibro Roller', 'Vibro roller', 'vibro-roller', 'vibro roller'])->findAll();
        $data = [
            'title' => 'Vibro Roller',
            'deskripsi' => 'Vibro Roller digunakan untuk pemadatan tanah dan aspal pada proyek konstruksi jalan dan bangunan.',
            'list' => $alat_berat
        ];
        return view('alat_berat/vibro_roller', $data);
    }

    public function dozer()
    {
        $alatBeratModel = new \App\Models\AlatBeratModel();
        // Cari semua varian Dozer
        $alat_berat = $alatBeratModel->whereIn('jenis', ['Dozer', 'dozer'])->findAll();
        $data = [
            'title' => 'Dozer',
            'deskripsi' => 'Dozer digunakan untuk mendorong material, meratakan tanah, dan pekerjaan berat lainnya di proyek konstruksi.',
            'list' => $alat_berat
        ];
        return view('alat_berat/dozer', $data);
    }

    public function motorGrader()
    {
        $alatBeratModel = new \App\Models\AlatBeratModel();
        // Cari semua varian Motor Grader
        $alat_berat = $alatBeratModel->whereIn('jenis', ['Motor Grader', 'Motor grader', 'motor-grader', 'motor grader'])->findAll();
        $data = [
            'title' => 'Motor Grader',
            'deskripsi' => 'Motor Grader digunakan untuk meratakan permukaan tanah dan membentuk kemiringan pada proyek jalan.',
            'list' => $alat_berat
        ];
        return view('alat_berat/motor_grader', $data);
    }

    public function wheelLoader()
    {
        $alatBeratModel = new \App\Models\AlatBeratModel();
        // Cari semua varian Wheel Loader
        $alat_berat = $alatBeratModel->whereIn('jenis', ['Wheel Loader', 'wheel-loader', 'wheel loader'])->findAll();
        $data = [
            'title' => 'Wheel Loader',
            'deskripsi' => 'Wheel Loader digunakan untuk memindahkan material lepas seperti tanah, pasir, dan kerikil.',
            'list' => $alat_berat
        ];
        return view('alat_berat/wheel_loader', $data);
    }

    public function backhoeLoader()
    {
        $alatBeratModel = new \App\Models\AlatBeratModel();
        // Cari semua varian Backhoe Loader
        $alat_berat = $alatBeratModel->whereIn('jenis', ['Backhoe Loader', 'Backhoe loader', 'backhoe-loader', 'backhoe loader'])->findAll();
        $data = [
            'title' => 'Backhoe Loader',
            'deskripsi' => 'Backhoe Loader adalah alat multifungsi untuk menggali, mengangkat, dan memindahkan material.',
            'list' => $alat_berat
        ];
        return view('alat_berat/backhoe_loader', $data);
    }

    public function dumpTruck()
    {
        $alatBeratModel = new \App\Models\AlatBeratModel();
        // Gabungkan semua varian Dump Truck dan Dump Truck Fuso
        $alat_berat = $alatBeratModel->whereIn('jenis', [
            'Dump Truck', 'Dump truck', 'dump-truck', 'dump truck',
            'Dump Truck Fuso', 'dump-truck-fuso', 'dump truck fuso'
        ])->findAll();
        $data = [
            'title' => 'Dump Truck',
            'deskripsi' => 'Dump Truck digunakan untuk mengangkut material lepas seperti pasir, kerikil, dan tanah.',
            'list' => $alat_berat
        ];
        return view('alat_berat/dump_truck', $data);
    }

    public function dumpTruckFuso()
    {
        $alatBeratModel = new \App\Models\AlatBeratModel();
        // Cari semua varian Dump Truck Fuso
        $alat_berat = $alatBeratModel->whereIn('jenis', ['Dump Truck Fuso', 'dump-truck-fuso', 'dump truck fuso'])->findAll();
        $data = [
            'title' => 'Dump Truck Fuso',
            'deskripsi' => 'Dump Truck Fuso adalah jenis dump truck dengan kapasitas dan spesifikasi khusus untuk kebutuhan transportasi material.',
            'list' => $alat_berat
        ];
        return view('alat_berat/dump_truck_fuso', $data);
    }

    public function detail($id)
    {
        log_message('debug', 'Detail method called with ID: ' . $id);
        
        $alat = $this->alatBeratModel->find($id);
        
        if (!$alat) {
            log_message('error', 'Alat berat not found with ID: ' . $id);
            return redirect()->to('/alat-berat')->with('error', 'Alat berat tidak ditemukan');
        }

        // Ambil galeri gambar
        $gambarModel = new AlatBeratGambarModel();
        $galeri = $gambarModel->getByAlatBerat($id);

        log_message('debug', 'Found alat berat: ' . json_encode($alat));

        $data = [
            'title' => 'Detail ' . $alat['nama'],
            'alat' => $alat,
            'galeri' => $galeri
        ];

        return view('alat_berat/detail', $data);
    }

    /**
     * Simpan permintaan kuotasi dari user/pengunjung
     */
    public function mintaKuotasi()
    {
        $riwayatModel = new RiwayatPenawaranModel();
        $alat_berat_id = $this->request->getPost('alat_berat_id');
        
        // Validasi telepon hanya angka
        $telepon_pengunjung = $this->request->getPost('telepon_pengunjung');
        if (!empty($telepon_pengunjung)) {
            // Hapus semua karakter selain angka
            $telepon_pengunjung = preg_replace('/[^0-9]/', '', $telepon_pengunjung);
            
            // Validasi panjang telepon (minimal 10, maksimal 15 digit)
            if (strlen($telepon_pengunjung) < 10 || strlen($telepon_pengunjung) > 15) {
                session()->setFlashdata('error', 'Nomor telepon harus berisi 10-15 digit angka');
                return redirect()->back()->withInput();
            }
        }

        $lama_sewa = (int) $this->request->getPost('lama_sewa');
        if ($lama_sewa < 8) {
            session()->setFlashdata('error', 'Lama sewa minimal 8 jam. Silakan masukkan 8 jam atau lebih.');
            return redirect()->back()->withInput();
        }

        $alat = $this->alatBeratModel->find($alat_berat_id);
        if (!$alat) {
            session()->setFlashdata('error', 'Alat berat tidak ditemukan.');
            return redirect()->back()->withInput();
        }

        $harga_ditawarkan = $this->request->getPost('harga_ditawarkan');
        $minHarga = !empty($alat['min_harga_penawaran']) && is_numeric($alat['min_harga_penawaran']) ? (float)$alat['min_harga_penawaran'] : 150000;
        $maxHarga = !empty($alat['harga_sewa']) && is_numeric($alat['harga_sewa']) ? (float)$alat['harga_sewa'] : null;

        if (!is_numeric($harga_ditawarkan) || $harga_ditawarkan < $minHarga) {
            session()->setFlashdata('error', 'Nominal tawaran tidak valid. Silakan masukkan nominal tawaran yang lebih tinggi.');
            return redirect()->back()->withInput();
        }
        if ($maxHarga !== null && $harga_ditawarkan > $maxHarga) {
            session()->setFlashdata('error', 'Nominal tawaran tidak valid. Silakan masukkan nominal tawaran yang lebih tinggi.');
            return redirect()->back()->withInput();
        }
        
        // Set timezone Jakarta untuk waktu permintaan
        $datetime = new \DateTime('now', new \DateTimeZone('Asia/Jakarta'));
        
        $data = [
            'alat_berat_id' => $alat_berat_id,
            'tanggal_minta' => $datetime->format('Y-m-d H:i:s'),
            'status' => 'pending',
            'telepon_pengunjung' => $telepon_pengunjung,
            'alamat_pengiriman' => $this->request->getPost('alamat_pengiriman'),
            'lama_sewa' => $lama_sewa,
        ];
        if (session('user_id')) {
            $data['user_id'] = session('user_id');
        } else {
            $data['nama_pengunjung'] = $this->request->getPost('nama_pengunjung');
            $data['email_pengunjung'] = $this->request->getPost('email_pengunjung');
            session()->set('email_pengunjung', $data['email_pengunjung']);
        }
        if ($this->request->getPost('catatan')) {
            $data['catatan'] = $this->request->getPost('catatan');
        }
        if ($this->request->getPost('harga_ditawarkan')) {
            $data['harga_ditawarkan'] = $this->request->getPost('harga_ditawarkan');
        }
        $riwayatModel->insert($data);
        // Notifikasi ke admin
        $alatModel = new \App\Models\AlatBeratModel();
        $notifikasiModel = new \App\Models\NotifikasiModel();
        $alat = $alatModel->find($alat_berat_id);
        $namaAlat = $alat ? $alat['nama'] : 'Alat Berat';
        $namaUser = session('user_name') ?? $this->request->getPost('nama_pengunjung') ?? $this->request->getPost('email_pengunjung') ?? 'User';
        $pesan = 'Ada penawaran baru dari <b>' . esc($namaUser) . '</b> untuk <b>' . esc($namaAlat) . '</b>';
        $notifikasiModel->insert([
            'user_id' => 1,
            'pesan' => $pesan,
            'link' => base_url('admin/penawaran'),
            'tipe' => 'kuotasi',
            'is_read' => 0
        ]);
        return redirect()->to('/')->with('message', 'Permintaan penawaran berhasil dikirim!');
    }

    /**
     * Tampilkan riwayat permintaan kuotasi (untuk user & admin)
     */
    public function riwayatKuotasi()
    {
        $riwayatModel = new RiwayatPenawaranModel();
        if (session('user_role') === 'admin') {
            // Admin: lihat semua
            $riwayat = $riwayatModel->orderBy('tanggal_minta', 'DESC')->findAll();
        } else if (session('user_id')) {
            // User login: lihat miliknya
            $riwayat = $riwayatModel->where('user_id', session('user_id'))->orderBy('tanggal_minta', 'DESC')->findAll();
        } else {
            // Pengunjung: berdasarkan email (jika ada di session)
            $email = session('email_pengunjung');
            if ($email) {
                $riwayat = $riwayatModel->where('email_pengunjung', $email)->orderBy('tanggal_minta', 'DESC')->findAll();
            } else {
                $riwayat = [];
            }
        }
        $alatModel = new AlatBeratModel();
        $userModel = new UserModel();
        $pembayaranModel = new PembayaranModel();
        foreach ($riwayat as &$r) {
            $alat = $alatModel->find($r['alat_berat_id']);
            $r['nama_alat'] = $alat ? $alat['nama'] : '-';
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
            $pembayaranList = $pembayaranModel
                ->where('riwayat_penawaran_id', $r['id'])
                ->orderBy('created_at', 'DESC')
                ->findAll();
            $r['pembayaran_status'] = null;
            $r['pembayaran_id'] = null;
            $r['pembayaran_deposit'] = null;
            $r['pembayaran_bank_pengirim'] = null;
            $r['pembayaran_nama_pengirim'] = null;
            $r['pembayaran_bank_tujuan'] = null;
            $r['pembayaran_rental_end_at'] = null;
            $pendingPembayaran = null;
            $verifiedPembayaran = null;
            $rejectedCount = 0;
            $lastRejected = null;
            foreach ($pembayaranList as $p) {
                $statusPembayaran = $p['status'] ?? 'pending';
                if ($statusPembayaran === 'pending' && $pendingPembayaran === null) {
                    $pendingPembayaran = $p;
                } elseif ($statusPembayaran === 'verified' && $verifiedPembayaran === null) {
                    $verifiedPembayaran = $p;
                } elseif ($statusPembayaran === 'rejected') {
                    $rejectedCount++;
                    $lastRejected = $p;
                }
            }
            if ($verifiedPembayaran !== null) {
                $r['lunas'] = true;
                $r['pembayaran_status'] = 'verified';
                $r['pembayaran_id'] = $verifiedPembayaran['id'];
                $lama = (int)($r['lama_sewa'] ?? 0);
                $unit = null;
                if (!empty($r['harga_ditawarkan']) && is_numeric($r['harga_ditawarkan'])) {
                    $unit = (float)$r['harga_ditawarkan'];
                } elseif ($alat && isset($alat['harga_sewa']) && is_numeric($alat['harga_sewa'])) {
                    $unit = (float)$alat['harga_sewa'];
                }
                $operatorUnit = ($alat && isset($alat['operator']) && is_numeric($alat['operator'])) ? (float)$alat['operator'] : 0;
                if ($lama > 0 && $unit !== null) {
                    $r['biaya_sewa'] = (int)($unit * $lama);
                    if ($operatorUnit > 0) {
                        $r['biaya_operator'] = (int)($operatorUnit * $lama);
                    }
                }
                $r['pembayaran_deposit'] = isset($verifiedPembayaran['deposit']) ? (int)$verifiedPembayaran['deposit'] : null;
                $r['pembayaran_bank_pengirim'] = $verifiedPembayaran['bank_pengirim'] ?? null;
                $r['pembayaran_nama_pengirim'] = $verifiedPembayaran['nama_pengirim'] ?? null;
                $r['pembayaran_bank_tujuan'] = $verifiedPembayaran['bank_tujuan'] ?? null;
                $r['pembayaran_rental_end_at'] = $verifiedPembayaran['rental_end_at'] ?? null;
                // Jangan hitung waktu sewa dari updated_at.
                // Timer hanya berjalan jika admin sudah memulai dengan menetapkan rental_end_at.
            } else {
                if ($pendingPembayaran !== null) {
                    $r['pembayaran_pending'] = true;
                    $r['pembayaran_status'] = 'pending';
                    $r['pembayaran_id'] = $pendingPembayaran['id'];
                    $r['pembayaran_deposit'] = isset($pendingPembayaran['deposit']) ? (int)$pendingPembayaran['deposit'] : null;
                    $r['pembayaran_bank_pengirim'] = $pendingPembayaran['bank_pengirim'] ?? null;
                    $r['pembayaran_nama_pengirim'] = $pendingPembayaran['nama_pengirim'] ?? null;
                    $r['pembayaran_bank_tujuan'] = $pendingPembayaran['bank_tujuan'] ?? null;
                } elseif ($lastRejected !== null) {
                    $r['pembayaran_status'] = 'rejected';
                    $r['pembayaran_id'] = $lastRejected['id'];
                    if (!empty($lastRejected['alasan_penolakan'])) {
                        $r['alasan_penolakan'] = $lastRejected['alasan_penolakan'];
                    }
                    $r['pembayaran_deposit'] = isset($lastRejected['deposit']) ? (int)$lastRejected['deposit'] : null;
                    $r['pembayaran_bank_pengirim'] = $lastRejected['bank_pengirim'] ?? null;
                    $r['pembayaran_nama_pengirim'] = $lastRejected['nama_pengirim'] ?? null;
                    $r['pembayaran_bank_tujuan'] = $lastRejected['bank_tujuan'] ?? null;
                }
            }
            if ($rejectedCount > 0) {
                $r['pembayaran_rejected'] = true;
                $r['pembayaran_rejected_count'] = $rejectedCount;
            }
        }
        $data = [
            'title' => 'Riwayat sewa alat berat',
            'riwayat' => $riwayat
        ];
        return view('alat_berat/riwayat_penawaran', $data);
    }

    public function hapusRiwayatKuotasi($id)
    {
        $riwayatModel = new \App\Models\RiwayatPenawaranModel();
        $riwayat = $riwayatModel->find($id);
        // Pastikan hanya user yang punya data yang bisa hapus
        if ($riwayat && isset($riwayat['user_id']) && $riwayat['user_id'] == session('user_id')) {
            $riwayatModel->delete($id);
            return redirect()->to('/alat-berat/riwayat-penawaran')->with('message', 'Permintaan kuotasi berhasil dihapus!');
        } else {
            return redirect()->to('/alat-berat/riwayat-penawaran')->with('error', 'Tidak diizinkan menghapus data ini!');
        }
    }

    public function ajukanUlangKuotasi($id)
    {
        $riwayatModel = new \App\Models\RiwayatPenawaranModel();
        $riwayat = $riwayatModel->find($id);
        if ($riwayat && isset($riwayat['user_id']) && $riwayat['user_id'] == session('user_id')) {
            // Cek jumlah pengajuan ulang
            $jumlahAjukanUlang = $riwayat['jumlah_ajukan_ulang'] ?? 0;
            if ($jumlahAjukanUlang >= 3) {
                return redirect()->to('/alat-berat/riwayat-penawaran')->with('error', 'Anda sudah mencapai batas maksimal pengajuan ulang (3 kali). Silakan hubungi admin untuk informasi lebih lanjut.');
            }
            
            // Update jumlah ajukan ulang
            $riwayatModel->update($id, [
                'jumlah_ajukan_ulang' => $jumlahAjukanUlang + 1,
                'status' => 'pending'
            ]);
            
            return redirect()->to('/alat-berat/riwayat-penawaran')->with('message', 'Harga penawaran berhasil diajukan ulang! (Pengajuan ulang ke-' . ($jumlahAjukanUlang + 1) . ' dari 3)');
        } else {
            return redirect()->to('/alat-berat/riwayat-penawaran')->with('error', 'Tidak diizinkan mengubah data ini!');
        }
    }

    public function mintaKuotasiForm($id)
    {
        $alat = $this->alatBeratModel->find($id);
        if (!$alat) {
            return redirect()->to('/alat-berat')->with('error', 'Alat berat tidak ditemukan');
        }
        $data = [
            'title' => 'Minta Penawaran Sewa Alat Berat',
            'alat' => $alat
        ];
        return view('alat_berat/form_penawaran', $data);
    }
} 
