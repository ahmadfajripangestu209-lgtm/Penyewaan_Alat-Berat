<?php
namespace App\Controllers;

use App\Models\PembayaranModel;
use App\Models\RiwayatPenawaranModel;
use App\Models\AlatBeratModel;
use Dompdf\Dompdf;
use Dompdf\Options;

class Pembayaran extends BaseController
{
    public function langsung($alatId = null)
    {
        if (!session('logged_in')) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu');
        }

        if ($alatId === null) {
            return redirect()->to('/alat-berat')->with('error', 'Pilih alat terlebih dahulu untuk pembayaran langsung.');
        }

        $alatModel = new AlatBeratModel();
        $alat = $alatModel->find($alatId);
        if (!$alat) {
            return redirect()->to('/alat-berat')->with('error', 'Alat berat tidak ditemukan');
        }
        $hargaUnit = (is_numeric($alat['harga_sewa'] ?? null)) ? (float)$alat['harga_sewa'] : 0;
        $operatorUnit = (is_numeric($alat['operator'] ?? null)) ? (float)$alat['operator'] : 0;
        return view('pembayaran/langsung', [
            'alat' => $alat,
            'harga_unit' => $hargaUnit,
            'harga_operator' => $operatorUnit,
        ]);
    }

    public function prosesLangsung()
    {
        if (!session('logged_in')) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu');
        }
        $alatId = (int)$this->request->getPost('alat_id');
        $lamaSewa = (int)$this->request->getPost('lama_sewa');
        if ($alatId <= 0 || $lamaSewa <= 0) {
            return redirect()->back()->with('error', 'Data tidak valid. Periksa alat dan lama sewa.')->withInput();
        }
        $alatModel = new AlatBeratModel();
        $alat = $alatModel->find($alatId);
        if (!$alat) {
            return redirect()->to('/alat-berat')->with('error', 'Alat berat tidak ditemukan');
        }
        $unit = (is_numeric($alat['harga_sewa'] ?? null)) ? (float)$alat['harga_sewa'] : 0;
        $operatorUnit = (is_numeric($alat['operator'] ?? null)) ? (float)$alat['operator'] : 0;
        $jumlah = (int)(($unit + $operatorUnit) * $lamaSewa);
        $deposit = (int) $this->request->getPost('deposit');
        if ($deposit <= 0) {
            $deposit = 3000000;
        }

        $metode = $this->request->getPost('metode_pembayaran');
        $newName = '';
        $bankPengirim = '';
        $namaPengirim = '';
        $bankTujuan = '';

        if ($metode === 'pakasir') {
            $bankPengirim = 'Pakasir';
            $namaPengirim = session('user_name');
            $bankTujuan = 'Pakasir';
            $newName = 'Pakasir - Automatic';
        } else {
            $bukti = $this->request->getFile('bukti_transfer');
            if (!$bukti || !$bukti->isValid()) {
                return redirect()->back()->with('error', 'Bukti transfer wajib diunggah')->withInput();
            }
            $newName = time() . '-' . $bukti->getRandomName();
            if (!is_dir('uploads/bukti_transfer')) {
                mkdir('uploads/bukti_transfer', 0777, true);
            }
            $bukti->move('uploads/bukti_transfer', $newName);
            
            $bankPengirim = $this->request->getPost('bank_pengirim');
            $namaPengirim = $this->request->getPost('nama_pengirim');
            $bankTujuan = $this->request->getPost('bank_tujuan');
        }

        $db = \Config\Database::connect();
        if (! $db->tableExists('pembayaran')) {
            $forge = \Config\Database::forge();
            $forge->addField([
                'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
                'riwayat_penawaran_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
                'user_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
                'nama_pemesan' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
                'email_pemesan' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
                'nama_alat' => ['type' => 'VARCHAR', 'constraint' => 150, 'null' => true],
                'jumlah' => ['type' => 'INT', 'constraint' => 11, 'null' => true],
                'deposit' => ['type' => 'INT', 'constraint' => 11, 'null' => true],
                'bank_pengirim' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
                'nama_pengirim' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
                'bank_tujuan' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
                'bukti_transfer' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
                'status' => ['type' => 'ENUM', 'constraint' => ['pending','verified','rejected'], 'default' => 'pending'],
                'created_at' => ['type' => 'DATETIME', 'null' => true],
                'updated_at' => ['type' => 'DATETIME', 'null' => true],
            ]);
            $forge->addKey('id', true);
            $forge->createTable('pembayaran', true);
        } else {
            $fields = $db->getFieldNames('pembayaran');
            $forge = \Config\Database::forge();
            if (!in_array('user_id', $fields)) {
                $forge->addColumn('pembayaran', [
                    'user_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
                ]);
            }
            if (!in_array('nama_pemesan', $fields)) {
                $forge->addColumn('pembayaran', [
                    'nama_pemesan' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
                ]);
            }
            if (!in_array('email_pemesan', $fields)) {
                $forge->addColumn('pembayaran', [
                    'email_pemesan' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
                ]);
            }
            if (!in_array('nama_alat', $fields)) {
                $forge->addColumn('pembayaran', [
                    'nama_alat' => ['type' => 'VARCHAR', 'constraint' => 150, 'null' => true],
                ]);
            }
            if (!in_array('jumlah', $fields)) {
                $forge->addColumn('pembayaran', [
                    'jumlah' => ['type' => 'INT', 'constraint' => 11, 'null' => true],
                ]);
            }
            if (!in_array('deposit', $fields)) {
                $forge->addColumn('pembayaran', [
                    'deposit' => ['type' => 'INT', 'constraint' => 11, 'null' => true],
                ]);
            }
            if (!in_array('bank_pengirim', $fields)) {
                $forge->addColumn('pembayaran', [
                    'bank_pengirim' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
                ]);
            }
            if (!in_array('nama_pengirim', $fields)) {
                $forge->addColumn('pembayaran', [
                    'nama_pengirim' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
                ]);
            }
            if (!in_array('bank_tujuan', $fields)) {
                $forge->addColumn('pembayaran', [
                    'bank_tujuan' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
                ]);
            }
            if (!in_array('bukti_transfer', $fields)) {
                $forge->addColumn('pembayaran', [
                    'bukti_transfer' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
                ]);
            }
            if (!in_array('status', $fields)) {
                $forge->addColumn('pembayaran', [
                    'status' => ['type' => 'ENUM', 'constraint' => ['pending','verified','rejected'], 'default' => 'pending'],
                ]);
            }
        }
        // Buat entri riwayat agar pembayaran langsung terhubung ke riwayat user
        $riwayatModel = new RiwayatPenawaranModel();
        $datetime = new \DateTime('now', new \DateTimeZone('Asia/Jakarta'));
        $totalEstimasi = ($unit + $operatorUnit) * $lamaSewa;
        $telp = preg_replace('/\D+/', '', (string)$this->request->getPost('telepon_pengunjung'));
        $alamat = $this->request->getPost('alamat_pengiriman');
        $riwayatId = $riwayatModel->insert([
            'user_id' => session('user_id'),
            'alat_berat_id' => $alatId,
            'tanggal_minta' => $datetime->format('Y-m-d H:i:s'),
            'status' => 'diterima',
            'lama_sewa' => $lamaSewa,
            'total_estimasi' => (int)$totalEstimasi,
            'telepon_pengunjung' => $telp ?: null,
            'alamat_pengiriman' => $alamat ?: null,
        ]);

        $pembayaranModel = new PembayaranModel();
        $pembayaranId = $pembayaranModel->insert([
            'riwayat_penawaran_id' => (int)$riwayatId,
            'user_id' => session('user_id'),
            'nama_pemesan' => session('user_name'),
            'email_pemesan' => session('user_email'),
            'nama_alat' => $alat['nama'] ?? $this->request->getPost('nama_alat'),
            'jumlah' => $jumlah,
            'deposit' => $deposit,
            'bank_pengirim' => $bankPengirim,
            'nama_pengirim' => $namaPengirim,
            'bank_tujuan' => $bankTujuan,
            'bukti_transfer' => $newName,
            'status' => 'pending',
        ]);

        $this->sendAdminPaymentNotification([ 
            'pembayaran_id' => $pembayaranId,
            'nama_alat' => $alat['nama'] ?? $this->request->getPost('nama_alat'),
            'jumlah' => $jumlah,
            'user_name' => session('user_name'),
        ]);

        if ($metode === 'pakasir') {
            $config = new \Config\Pakasir();
            $slug = $config->slug;
            $totalAmount = $jumlah + $deposit;
            $order_id = "EDJ-" . $pembayaranId;
            $redirectUrl = base_url('pembayaran/sukses');
            $pakasirUrl = "https://app.pakasir.com/pay/{$slug}/{$totalAmount}?order_id={$order_id}&redirect=" . urlencode($redirectUrl);
            return redirect()->to($pakasirUrl);
        }

        return redirect()->to('/pembayaran/sukses');
    }

    public function index($riwayatId)
    {
        if (!session('logged_in')) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu');
        }
        $riwayatModel = new RiwayatPenawaranModel();
        $alatModel = new AlatBeratModel();
        $pembayaranModel = new PembayaranModel();
        $riwayat = $riwayatModel->find($riwayatId);
        if (!$riwayat || $riwayat['user_id'] != session('user_id')) {
            return redirect()->to('/alat-berat/riwayat-penawaran')->with('error', 'Data tidak ditemukan');
        }
        $pending = $pembayaranModel->where('riwayat_penawaran_id', $riwayatId)->where('status', 'pending')->first();
        if (!empty($pending)) {
            return redirect()->to('/alat-berat/riwayat-penawaran')->with('error', 'Pembayaran Anda sedang menunggu verifikasi admin');
        }
        $verified = $pembayaranModel->where('riwayat_penawaran_id', $riwayatId)->where('status', 'verified')->first();
        if (!empty($verified)) {
            return redirect()->to('/alat-berat/riwayat-penawaran')->with('message', 'Pembayaran sudah terverifikasi');
        }
        $alat = $alatModel->find($riwayat['alat_berat_id']);
        // Hitung total estimasi sesuai kartu riwayat
        $lama = (int)($riwayat['lama_sewa'] ?? 0);
        $unit = null;
        if (!empty($riwayat['harga_ditawarkan']) && is_numeric($riwayat['harga_ditawarkan'])) {
            $unit = (float)$riwayat['harga_ditawarkan'];
        } else if ($alat && isset($alat['harga_sewa']) && is_numeric($alat['harga_sewa'])) {
            $unit = (float)$alat['harga_sewa'];
        }
        $operatorUnit = ($alat && isset($alat['operator']) && is_numeric($alat['operator'])) ? (float)$alat['operator'] : 0;
        $totalEstimasi = ($lama > 0 && $unit !== null) ? (int)((($unit + $operatorUnit)) * $lama) : 0;
        return view('pembayaran/index', [
            'riwayat' => $riwayat,
            'alat' => $alat,
            'total_estimasi' => $totalEstimasi,
            'harga_operator' => $operatorUnit,
        ]);
    }

    public function proses()
    {
        if (!session('logged_in')) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu');
        }
        $riwayatId = $this->request->getPost('riwayat_penawaran_id');
        $riwayatModel = new RiwayatPenawaranModel();
        $riwayat = $riwayatModel->find($riwayatId);
        if (!$riwayat || $riwayat['user_id'] != session('user_id')) {
            return redirect()->to('/alat-berat/riwayat-penawaran')->with('error', 'Data tidak ditemukan');
        }

        // Fallback: samakan jumlah dengan total estimasi jika input tidak valid
        $jumlah = (int) $this->request->getPost('jumlah');
        if ($jumlah <= 0) {
            $alatModel = new AlatBeratModel();
            $alat = $alatModel->find($riwayat['alat_berat_id'] ?? 0);
            $lama = (int)($riwayat['lama_sewa'] ?? 0);
            $unit = null;
            if (!empty($riwayat['harga_ditawarkan']) && is_numeric($riwayat['harga_ditawarkan'])) {
                $unit = (float)$riwayat['harga_ditawarkan'];
            } else if ($alat && isset($alat['harga_sewa']) && is_numeric($alat['harga_sewa'])) {
                $unit = (float)$alat['harga_sewa'];
            }
            $jumlah = ($lama > 0 && $unit !== null) ? (int)($unit * $lama) : 0;
        }
        $deposit = (int) $this->request->getPost('deposit');
        if ($deposit <= 0) {
            $deposit = 3000000;
        }

        $metode = $this->request->getPost('metode_pembayaran');
        $newName = '';
        $bankPengirim = '';
        $namaPengirim = '';
        $bankTujuan = '';

        if ($metode === 'pakasir') {
            $bankPengirim = 'Pakasir';
            $namaPengirim = session('user_name');
            $bankTujuan = 'Pakasir';
            $newName = 'Pakasir - Automatic';
        } else {
            $bukti = $this->request->getFile('bukti_transfer');
            if (!$bukti || !$bukti->isValid()) {
                return redirect()->back()->with('error', 'Bukti transfer wajib diunggah')->withInput();
            }
            $newName = time() . '-' . $bukti->getRandomName();
            if (!is_dir('uploads/bukti_transfer')) {
                mkdir('uploads/bukti_transfer', 0777, true);
            }
            $bukti->move('uploads/bukti_transfer', $newName);
            
            $bankPengirim = $this->request->getPost('bank_pengirim');
            $namaPengirim = $this->request->getPost('nama_pengirim');
            $bankTujuan = $this->request->getPost('bank_tujuan');
        }

        $db = \Config\Database::connect();
        if (! $db->tableExists('pembayaran')) {
            $forge = \Config\Database::forge();
            $forge->addField([
                'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
                'riwayat_penawaran_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
                'user_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
                'nama_pemesan' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
                'email_pemesan' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
                'nama_alat' => ['type' => 'VARCHAR', 'constraint' => 150, 'null' => true],
                'jumlah' => ['type' => 'INT', 'constraint' => 11, 'null' => true],
                'deposit' => ['type' => 'INT', 'constraint' => 11, 'null' => true],
                'bank_pengirim' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
                'nama_pengirim' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
                'bank_tujuan' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
                'bukti_transfer' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
                'status' => ['type' => 'ENUM', 'constraint' => ['pending','verified','rejected'], 'default' => 'pending'],
                'created_at' => ['type' => 'DATETIME', 'null' => true],
                'updated_at' => ['type' => 'DATETIME', 'null' => true],
            ]);
            $forge->addKey('id', true);
            $forge->createTable('pembayaran', true);
        } else {
            $fields = $db->getFieldNames('pembayaran');
            $forge = \Config\Database::forge();
            if (!in_array('user_id', $fields)) {
                $forge->addColumn('pembayaran', [
                    'user_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
                ]);
            }
            if (!in_array('nama_pemesan', $fields)) {
                $forge->addColumn('pembayaran', [
                    'nama_pemesan' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
                ]);
            }
            if (!in_array('email_pemesan', $fields)) {
                $forge->addColumn('pembayaran', [
                    'email_pemesan' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
                ]);
            }
            if (!in_array('nama_alat', $fields)) {
                $forge->addColumn('pembayaran', [
                    'nama_alat' => ['type' => 'VARCHAR', 'constraint' => 150, 'null' => true],
                ]);
            }
            if (!in_array('jumlah', $fields)) {
                $forge->addColumn('pembayaran', [
                    'jumlah' => ['type' => 'INT', 'constraint' => 11, 'null' => true],
                ]);
            }
            if (!in_array('deposit', $fields)) {
                $forge->addColumn('pembayaran', [
                    'deposit' => ['type' => 'INT', 'constraint' => 11, 'null' => true],
                ]);
            }
            if (!in_array('bank_pengirim', $fields)) {
                $forge->addColumn('pembayaran', [
                    'bank_pengirim' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
                ]);
            }
            if (!in_array('nama_pengirim', $fields)) {
                $forge->addColumn('pembayaran', [
                    'nama_pengirim' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
                ]);
            }
            if (!in_array('bank_tujuan', $fields)) {
                $forge->addColumn('pembayaran', [
                    'bank_tujuan' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
                ]);
            }
            if (!in_array('bukti_transfer', $fields)) {
                $forge->addColumn('pembayaran', [
                    'bukti_transfer' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
                ]);
            }
            if (!in_array('status', $fields)) {
                $forge->addColumn('pembayaran', [
                    'status' => ['type' => 'ENUM', 'constraint' => ['pending','verified','rejected'], 'default' => 'pending'],
                ]);
            }
        }
        $pembayaranModel = new PembayaranModel();
        $existing = $pembayaranModel
            ->where('riwayat_penawaran_id', $riwayatId)
            ->whereIn('status', ['pending', 'verified'])
            ->first();
        if (!empty($existing)) {
            return redirect()->to('/alat-berat/riwayat-penawaran')->with('error', 'Pembayaran telah dikirim atau terverifikasi. Tunggu verifikasi admin.');
        }

        $pembayaranId = $pembayaranModel->insert([
            'riwayat_penawaran_id' => $riwayatId,
            'user_id' => session('user_id'),
            'nama_pemesan' => session('user_name'),
            'email_pemesan' => session('user_email'),
            'nama_alat' => $this->request->getPost('nama_alat'),
            'jumlah' => $jumlah,
            'deposit' => $deposit,
            'bank_pengirim' => $bankPengirim,
            'nama_pengirim' => $namaPengirim,
            'bank_tujuan' => $bankTujuan,
            'bukti_transfer' => $newName,
            'status' => 'pending',
        ]);

        $this->sendAdminPaymentNotification([
            'pembayaran_id' => $pembayaranId,
            'nama_alat' => $this->request->getPost('nama_alat'),
            'jumlah' => $jumlah,
            'user_name' => session('user_name'),
        ]);

        if ($metode === 'pakasir') {
            $config = new \Config\Pakasir();
            $slug = $config->slug;
            $totalAmount = $jumlah + $deposit;
            $order_id = "EDJ-" . $pembayaranId;
            $redirectUrl = base_url('pembayaran/sukses');
            $pakasirUrl = "https://app.pakasir.com/pay/{$slug}/{$totalAmount}?order_id={$order_id}&redirect=" . urlencode($redirectUrl);
            return redirect()->to($pakasirUrl);
        }

        return redirect()->to('/pembayaran/sukses');
    }

    public function sukses()
    {
        return view('pembayaran/sukses');
    }

    private function sendAdminPaymentNotification(array $data)
    {
        $userName = $data['user_name'] ?? 'Pengguna';
        $namaAlat = $data['nama_alat'] ?? 'Alat Berat';
        $jumlah = isset($data['jumlah']) ? number_format((int)$data['jumlah'], 0, ',', '.') : '0';
        $pembayaranId = $data['pembayaran_id'] ?? null;

        $notifikasiModel = new \App\Models\NotifikasiModel();
        $adminModel = new \App\Models\UserModel();
        $admins = $adminModel->where('role', 'admin')->findAll();

        $pesan = 'Pembayaran baru dari <b>' . esc($userName) . '</b> untuk <b>' . esc($namaAlat) . '</b> sebesar <b>Rp ' . $jumlah . '</b>. Silakan verifikasi.';
        $link = base_url('admin/pembayaran');

        foreach ($admins as $admin) {
            $notifikasiModel->insert([
                'user_id' => $admin['id'],
                'pesan' => $pesan,
                'link' => $link,
                'tipe' => 'pesan',
                'is_read' => 0,
            ]);
        }
    }

    public function invoice($pembayaranId)
    {
        if (!session('logged_in')) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu');
        }

        $pembayaranModel = new PembayaranModel();
        $pembayaran = $pembayaranModel->find($pembayaranId);
        if (!$pembayaran) {
            return redirect()->to('/alat-berat/riwayat-penawaran')->with('error', 'Pembayaran tidak ditemukan');
        }
        if (empty($pembayaran['user_id']) || $pembayaran['user_id'] != session('user_id')) {
            return redirect()->to('/alat-berat/riwayat-penawaran')->with('error', 'Tidak diizinkan mengunduh faktur ini');
        }

        if (!empty($pembayaran['status']) && $pembayaran['status'] === 'rejected') {
            return redirect()->to('/alat-berat/riwayat-penawaran')->with('error', 'Faktur tidak tersedia untuk pembayaran yang ditolak');
        }

        $company = [];
        try {
            $company = require APPPATH . 'Config/profil_perusahaan.php';
        } catch (\Throwable $e) {
            $company = [];
        }

        $riwayat = null;
        if (!empty($pembayaran['riwayat_penawaran_id'])) {
            $riwayatModel = new RiwayatPenawaranModel();
            $riwayat = $riwayatModel->find($pembayaran['riwayat_penawaran_id']);
        }

        $html = view('pembayaran/invoice', [
            'pembayaran' => $pembayaran,
            'company_name' => strtoupper($company['hero_title'] ?? 'Nama Perusahaan'),
            'company_address' => $company['alamat'] ?? '',
            'company_phone' => $company['telepon'] ?? '+62 887-0707-7027',
            'company_email' => $company['email'] ?? 'info@edijayajambi.com',
            'company_website' => $company['website'] ?? 'www.edijayajambi.com',
            'company_location' => $company['alamat'] ?? '',
            'company_desc' => $company['deskripsi'] ?? 'Solusi terbaik untuk kebutuhan proyek Anda.',
            'company_subtitle' => $company['subjudul'] ?? 'Penyewaan Alat Berat Terpercaya',
            'company_header_image' => $company['foto_header'] ?? 'default-header.jpg',
            'note' => $company['catatan_faktur'] ?? null,
            'lama_sewa' => $riwayat['lama_sewa'] ?? null,
        ]);

        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $pdf = $dompdf->output();
        return $this->response
            ->setHeader('Content-Type', 'application/pdf')
            ->setHeader('Content-Disposition', 'inline; filename="faktur_' . $pembayaranId . '.pdf"')
            ->setBody($pdf);
    }

    public function webhook()
    {
        // Jika diakses via GET (testing di browser development), tampilkan info endpoint
        if (strtolower($this->request->getMethod()) === 'get') {
            return $this->response->setJSON([
                'status'  => 'ok',
                'message' => 'Pakasir Webhook Endpoint aktif. Gunakan metode POST untuk mengirim callback pembayaran.',
                'url'     => base_url('pembayaran/webhook'),
                'method'  => 'POST',
                'payload_example' => [
                    'amount'         => 3500000,
                    'order_id'       => 'EDJ-1',
                    'project'        => 'alatberat',
                    'status'         => 'completed',
                    'payment_method' => 'qris',
                    'completed_at'   => date('c'),
                ],
            ]);
        }

        $json = $this->request->getJSON(true);
        if (!$json) {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'Invalid payload']);
        }

        $amount = $json['amount'] ?? null;
        $orderId = $json['order_id'] ?? null;
        $project = $json['project'] ?? null;
        $status = $json['status'] ?? null;

        if (!$orderId || !$amount || !$status) {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'Missing parameters']);
        }

        // Parse payment ID dari order_id (contoh: EDJ-123)
        $parts = explode('-', $orderId);
        $paymentId = (int)end($parts);

        $pembayaranModel = new \App\Models\PembayaranModel();
        $pembayaran = $pembayaranModel->find($paymentId);

        if (!$pembayaran) {
            return $this->response->setStatusCode(404)->setJSON(['error' => 'Payment not found']);
        }

        // Security check: Panggil Pakasir Detail API untuk validasi transaksi
        $config = new \Config\Pakasir();
        $slug = $config->slug;
        $apiKey = $config->api_key;
        
        $client = \Config\Services::curlrequest();
        $verifyUrl = "https://app.pakasir.com/api/transactiondetail?project=" . urlencode($slug) . "&amount=" . urlencode($amount) . "&order_id=" . urlencode($orderId) . "&api_key=" . urlencode($apiKey);
        
        try {
            $response = $client->get($verifyUrl, [
                'headers' => [
                    'Accept' => 'application/json',
                ]
            ]);
            $resData = json_decode($response->getBody(), true);
            
            if (isset($resData['transaction']['status']) && $resData['transaction']['status'] === 'completed') {
                // Update status pembayaran ke verified (lunas)
                $updateData = ['status' => 'verified'];
                // rental_end_at will be set manually from Admin::aktifkanTimer when admin starts the timer
                $pembayaranModel->update($paymentId, $updateData);

                // Kirim notifikasi sistem ke user
                $alatModel = new \App\Models\AlatBeratModel();
                $alat = $riwayat ? $alatModel->find($riwayat['alat_berat_id']) : null;
                $namaAlat = $alat ? $alat['nama'] : ($pembayaran['nama_alat'] ?? 'Alat Berat');
                $userId = $pembayaran['user_id'] ?? ($riwayat['user_id'] ?? null);
                
                if (!empty($userId)) {
                    $notifikasiModel = new \App\Models\NotifikasiModel();
                    $pesan = 'Pembayaran otomatis Anda untuk <b>' . esc($namaAlat) . '</b> telah <b>terverifikasi otomatis</b> melalui Pakasir. Status: <b>Lunas</b>.';
                    $link = base_url('alat-berat/riwayat-penawaran');
                    $notifikasiModel->insert([
                        'user_id' => $userId,
                        'pesan' => $pesan,
                        'link' => $link,
                        'tipe' => 'kuotasi',
                        'is_read' => 0
                    ]);
                }

                return $this->response->setJSON(['status' => 'success', 'message' => 'Payment verified successfully']);
            } else {
                return $this->response->setStatusCode(400)->setJSON(['error' => 'Transaction not completed or verification failed']);
            }
        } catch (\Throwable $e) {
            return $this->response->setStatusCode(500)->setJSON(['error' => 'API Verification Error: ' . $e->getMessage()]);
        }
    }
}
