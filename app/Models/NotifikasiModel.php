<?php
namespace App\Models;
use CodeIgniter\Model;

class NotifikasiModel extends Model
{
    protected $table = 'notifikasi';
    protected $primaryKey = 'id';
    protected $allowedFields = ['user_id', 'pesan', 'link', 'tipe', 'is_read', 'created_at', 'updated_at'];
    protected $useTimestamps = true;
} 