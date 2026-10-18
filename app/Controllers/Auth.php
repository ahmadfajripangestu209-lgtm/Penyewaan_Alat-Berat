<?php
namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Controller;

class Auth extends BaseController
{
    public function register()
    {
        return view('auth/register');
    }

    public function registerProcess()
    {
        $validation =  \Config\Services::validation();
        $rules = [
            'name' => 'required',
            'email' => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[6]'
        ];
        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }
        $userModel = new UserModel();
        $userModel->save([
            'name' => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'role' => 'user',
        ]);
        return redirect()->to('/login')->with('message', 'Registrasi berhasil, silakan login!');
    }

    public function login()
    {
        return redirect()->to('/login-user');
    }

    public function loginProcess()
    {
        $userModel = new UserModel();
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        $user = $userModel->where('email', $email)->first();
        if ($user && password_verify($password, $user['password'])) {
            session()->set([
                'user_id' => $user['id'],
                'user_name' => $user['name'],
                'user_email' => $user['email'],
                'user_role' => $user['role'],
                'logged_in' => true
            ]);
            return redirect()->to('/');
        } else {
            return redirect()->back()->withInput()->with('error', 'Email atau password salah!');
        }
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login-user');
    }

    public function registerAdmin()
    {
        if (session()->get('user_role') !== 'admin') {
            return redirect()->to('/');
        }
        return view('auth/register_admin');
    }

    public function registerAdminProcess()
    {
        if (session()->get('user_role') !== 'admin') {
            return redirect()->to('/');
        }
        $validation =  \Config\Services::validation();
        $rules = [
            'name' => 'required',
            'email' => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[6]'
        ];
        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }
        $userModel = new UserModel();
        $userModel->save([
            'name' => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'role' => 'admin',
        ]);
        return redirect()->to('/')->with('message', 'Admin berhasil didaftarkan!');
    }

    public function loginAdmin()
    {
        return view('auth/login_admin');
    }
    public function loginAdminProcess()
    {
        $userModel = new \App\Models\UserModel();
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        $user = $userModel->where('email', $email)->first();
        if ($user && password_verify($password, $user['password']) && $user['role'] === 'admin') {
            session()->set([
                'user_id' => $user['id'],
                'user_name' => $user['name'],
                'user_email' => $user['email'],
                'user_role' => $user['role'],
                'logged_in' => true
            ]);
            return redirect()->to('/admin');
        } else {
            return redirect()->back()->withInput()->with('error', 'Email, password, atau role tidak sesuai!');
        }
    }
    public function loginUser()
    {
        return view('auth/login_user');
    }
    public function loginUserProcess()
    {
        $userModel = new \App\Models\UserModel();
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        $user = $userModel->where('email', $email)->first();
        if ($user && password_verify($password, $user['password']) && $user['role'] === 'user') {
            session()->set([
                'user_id' => $user['id'],
                'user_name' => $user['name'],
                'user_email' => $user['email'],
                'user_role' => $user['role'],
                'logged_in' => true
            ]);
            return redirect()->to('/');
        } else {
            return redirect()->back()->withInput()->with('error', 'Email, password, atau role tidak sesuai!');
        }
    }

    public function lupaPassword()
    {
        if ($this->request->getMethod() === 'post') {
            $email = $this->request->getPost('email');
            // Di sini bisa ditambahkan logika kirim email reset password
            // Untuk demo, tampilkan pesan sukses saja
            return redirect()->back()->with('message', 'Jika email terdaftar, link reset password telah dikirim ke email Anda.');
        }
        return view('auth/lupa_password');
    }
} 