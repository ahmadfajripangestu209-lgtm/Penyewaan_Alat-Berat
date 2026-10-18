<?php

namespace App\Models;

use CodeIgniter\Model;

class AlatBeratGambarModel extends Model
{
    protected $table = 'alat_berat_gambar';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'alat_berat_id',
        'filename',
        'created_at',
        'updated_at',
    ];
    protected $useTimestamps = true;

    // Ambil semua gambar untuk alat berat tertentu
    public function getByAlatBerat($alat_berat_id)
    {
        return $this->where('alat_berat_id', $alat_berat_id)->findAll();
    }
} 