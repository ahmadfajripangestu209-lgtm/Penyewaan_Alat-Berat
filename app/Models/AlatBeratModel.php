<?php

namespace App\Models;

use CodeIgniter\Model;

class AlatBeratModel extends Model
{
    protected $table = 'alat_berat';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = [
        'nama', 
        'tipe',
        'jenis', 
        'kapasitas', 
        'status', 
        'deskripsi',
        'gambar',
        'harga_sewa',
        'min_harga_penawaran',
        'jenis',
        'operator',
        'berat_operasional',
        'berat_operasional_ket',
        'kapasitas_bucket',
        'kapasitas_bucket_ket',
        'kedalaman_gali_maksimum',
        'kedalaman_gali_maksimum_ket',
        'tinggi_transportasi',
        'tinggi_transportasi_ket',
        'panjang_transportasi',
        'panjang_transportasi_ket',
        'lebar_transportasi',
        'lebar_transportasi_ket',
        'merek'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'nama' => 'required|min_length[3]',
        'jenis' => 'required',
        'kapasitas' => 'required|numeric',
        'status' => 'required|in_list[tersedia,dipinjam,rusak]',
        'deskripsi' => 'permit_empty',
        'gambar' => 'permit_empty',
        'harga_sewa' => 'permit_empty|numeric',
        'min_harga_penawaran' => 'permit_empty|numeric',
        'jenis' => 'permit_empty',
        'berat_operasional' => 'permit_empty|numeric',
        'kapasitas_bucket' => 'permit_empty|numeric',
        'kedalaman_gali_maksimum' => 'permit_empty|numeric',
        'tinggi_transportasi' => 'permit_empty|numeric',
        'panjang_transportasi' => 'permit_empty|numeric',
        'lebar_transportasi' => 'permit_empty|numeric',
        'merek' => 'permit_empty'
    ];

    protected $validationMessages = [
        'nama' => [
            'required' => 'Nama alat berat harus diisi',
            'min_length' => 'Nama alat berat minimal 3 karakter'
        ],
        'jenis' => [
            'required' => 'Jenis alat berat harus diisi'
        ],
        'kapasitas' => [
            'required' => 'Kapasitas harus diisi',
            'numeric' => 'Kapasitas harus berupa angka'
        ],
        'status' => [
            'required' => 'Status harus diisi',
            'in_list' => 'Status tidak valid'
        ]
    ];
} 
