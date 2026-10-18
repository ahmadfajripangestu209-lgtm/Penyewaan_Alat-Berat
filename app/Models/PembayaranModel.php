<?php

namespace App\Models;

use CodeIgniter\Model;

class PembayaranModel extends Model
{
    protected $table = 'pembayaran';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'riwayat_penawaran_id',
        'user_id',
        'nama_pemesan',
        'email_pemesan',
        'nama_alat',
        'jumlah',
        'deposit',
        'bank_pengirim',
        'nama_pengirim',
        'bank_tujuan',
        'bukti_transfer',
        'alasan_penolakan',
        'status',
        'rental_end_at',
        'show_in_durasi',
        'rental_expiry_notified',
        'created_at',
        'updated_at',
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
}
