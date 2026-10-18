<?php

namespace App\Controllers;

use App\Models\AlatBeratModel;

class Home extends BaseController
{
    protected $alatBeratModel;

    public function __construct()
    {
        $this->alatBeratModel = new AlatBeratModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Edi Jaya - Penyewaan Alat Berat',
            'alat_berat' => $this->alatBeratModel->where('status', 'tersedia')->findAll()
        ];

        return view('home/index', $data);
    }

    public function kontak()
    {
        return view('kontak');
    }

    public function tentang()
    {
        return view('tentang');
    }
}
