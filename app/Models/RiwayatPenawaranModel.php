<?php

namespace App\Models;

use CodeIgniter\Model;

class RiwayatPenawaranModel extends Model
{
    protected $table = 'riwayat_penawaran';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'user_id',
        'nama_pengunjung',
        'email_pengunjung',
        'telepon_pengunjung',
        'alamat_pengiriman',
        'lama_sewa',
        'alat_berat_id',
        'tanggal_minta',
        'status',
        'harga_ditawarkan',
        'total_estimasi',
        'catatan',
        'pesan_admin',
        'jumlah_ajukan_ulang',
        'created_at',
        'updated_at',
    ];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
} 
