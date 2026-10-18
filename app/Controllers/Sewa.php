<?php
namespace App\Controllers;

use CodeIgniter\Controller;

class Sewa extends Controller
{
    public function index()
    {
        $alat = $this->request->getGet('alat');
        return view('sewa', [
            'alat' => ['nama' => $alat]
        ]);
    }

    public function proses()
    {
        $nama = $this->request->getPost('nama');
        $email = $this->request->getPost('email');
        $telepon = $this->request->getPost('telepon');
        $alat = $this->request->getPost('alat');
        $pesan = $this->request->getPost('pesan');

        // Konfigurasi Mailtrap
        $emailService = \Config\Services::email();
        $config = [
            'protocol' => 'smtp',
            'SMTPHost' => 'sandbox.smtp.mailtrap.io',
            'SMTPPort' => 2525,
            'SMTPUser' => '62ec6a1a1c7711',
            'SMTPPass' => 'e12222da936197',
            'SMTPCrypto' => 'tls',
            'mailType' => 'html',
            'charset' => 'utf-8',
            'newline' => "\r\n",
            'crlf' => "\r\n"
        ];
        $emailService->initialize($config);
        $emailService->setFrom('no-reply@edijayajambi.com', 'Edi Jaya Jambi');
        $emailService->setTo('admin@edijayajambi.com');
        $emailService->setSubject('Permintaan Kuotasi Sewa Alat Berat');
        $body = '<div style="font-family:Arial,sans-serif;max-width:500px;margin:auto;">
            <h2 style="color:#f5a623;margin-bottom:8px;">Permintaan Kuotasi Sewa Alat Berat</h2>
            <p style="margin-bottom:18px;">Ada permintaan kuotasi baru dari website Edi Jaya Jambi. Berikut detailnya:</p>
            <table style="width:100%;border-collapse:collapse;">
                <tr><td style="padding:8px 0;width:140px;color:#888;">Nama</td><td style="padding:8px 0;">: <b>' . esc($nama) . '</b></td></tr>
                <tr><td style="padding:8px 0;color:#888;">Email</td><td style="padding:8px 0;">: ' . esc($email) . '</td></tr>
                <tr><td style="padding:8px 0;color:#888;">Telepon</td><td style="padding:8px 0;">: ' . esc($telepon) . '</td></tr>
                <tr><td style="padding:8px 0;color:#888;">Alat Berat</td><td style="padding:8px 0;">: <span style="color:#f5a623;font-weight:bold;">' . esc($alat) . '</span></td></tr>
                <tr><td style="padding:8px 0;color:#888;vertical-align:top;">Pesan</td><td style="padding:8px 0;">: ' . nl2br(htmlspecialchars($pesan)) . '</td></tr>
            </table>
            <hr style="margin:24px 0;">
            <div style="color:#888;font-size:13px;">Email ini dikirim otomatis dari website <a href="https://edijayajambi.com" style="color:#f5a623;text-decoration:none;">edijayajambi.com</a></div>
        </div>';
        $emailService->setMessage($body);
        $emailService->send();

        return view('sewa_sukses', [
            'nama' => $nama,
            'alat' => $alat
        ]);
    }
} 