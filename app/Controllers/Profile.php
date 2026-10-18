<?php

namespace App\Controllers;

use App\Models\UserModel;

class Profile extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function index()
    {
        if (!session('logged_in')) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu');
        }

        $user = $this->userModel->find(session('user_id'));
        $data = [
            'title' => 'Profil Saya',
            'user' => $user
        ];

        return view('profile/index', $data);
    }

    public function update()
    {
        if (!session('logged_in')) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu');
        }

        $rules = [
            'name' => 'required|min_length[3]',
            'email' => 'required|valid_email',
            'phone' => 'permit_empty|min_length[10]|max_length[15]',
            'address' => 'permit_empty'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $userId = session('user_id');
        $data = [
            'name' => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
            'phone' => $this->request->getPost('phone'),
            'address' => $this->request->getPost('address')
        ];

        // Jika ada password baru
        $newPassword = $this->request->getPost('new_password');
        if ($newPassword) {
            $currentPassword = $this->request->getPost('current_password');
            $user = $this->userModel->find($userId);
            
            if (!password_verify($currentPassword, $user['password'])) {
                return redirect()->back()->with('error', 'Password saat ini tidak sesuai');
            }
            
            if (strlen($newPassword) < 6) {
                return redirect()->back()->with('error', 'Password baru minimal 6 karakter');
            }
            
            $data['password'] = password_hash($newPassword, PASSWORD_DEFAULT);
        }

        $this->userModel->update($userId, $data);
        return redirect()->to('/profile')->with('message', 'Profil berhasil diperbarui');
    }

    public function notifikasi()
    {
        if (!session('logged_in')) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu');
        }
        $notifikasiModel = new \App\Models\NotifikasiModel();
        $notifikasi = $notifikasiModel->where('user_id', session('user_id'))
            ->orderBy('created_at', 'DESC')
            ->findAll();
        $data = [
            'title' => 'Notifikasi',
            'notifikasi' => $notifikasi
        ];
        return view('profile/notifikasi', $data);
    }

    public function bacaNotifikasi($id)
    {
        if (!session('logged_in')) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu');
        }
        $notifikasiModel = new \App\Models\NotifikasiModel();
        $notif = $notifikasiModel->find($id);
        if ($notif && $notif['user_id'] == session('user_id')) {
            $notifikasiModel->update($id, ['is_read' => 1]);
            $redirect = $notif['link'] ?: base_url('notifikasi');
            return redirect()->to($redirect);
        }
        return redirect()->to('/notifikasi')->with('error', 'Notifikasi tidak ditemukan');
    }

    public function hapusNotifikasi($id)
    {
        if (!session('logged_in')) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu');
        }
        $notifikasiModel = new \App\Models\NotifikasiModel();
        $notif = $notifikasiModel->find($id);
        if ($notif && $notif['user_id'] == session('user_id')) {
            $notifikasiModel->delete($id);
            return redirect()->to('/notifikasi')->with('message', 'Notifikasi berhasil dihapus');
        }
        return redirect()->to('/notifikasi')->with('error', 'Notifikasi tidak ditemukan');
    }

    public function hapusSemuaNotifikasi()
    {
        if (!session('logged_in')) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu');
        }
        $notifikasiModel = new \App\Models\NotifikasiModel();
        $notifikasiModel->where('user_id', session('user_id'))->delete();
        return redirect()->to('/notifikasi')->with('message', 'Semua notifikasi berhasil dihapus');
    }
} 