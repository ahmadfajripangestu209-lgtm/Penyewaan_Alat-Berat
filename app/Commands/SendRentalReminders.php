<?php
namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class SendRentalReminders extends BaseCommand
{
    protected $group = 'rental';
    protected $name = 'rental:send-reminders';
    protected $description = 'Sends notifications for rentals that have expired (rental_end_at <= now)';

    public function run(array $params = [])
    {
        self::sendNotifications(true);
    }

    public static function sendNotifications(bool $verbose = false): int
    {
        helper('url');

        $db = \Config\Database::connect();
        $now = date('Y-m-d H:i:s');

        // Find verified payments whose rental_end_at has passed and not yet notified
        $builder = $db->table('pembayaran p');
        $builder->select('p.*');
        $builder->where('p.status', 'verified');
        $builder->where('p.rental_end_at IS NOT NULL', null, false);
        $builder->where('p.rental_end_at <=', $now);

        $fields = $db->getFieldNames('pembayaran');
        if (in_array('rental_expiry_notified', $fields)) {
            $builder->groupStart();
            $builder->where('p.rental_expiry_notified IS NULL', null, false);
            $builder->orWhere('p.rental_expiry_notified', 0);
            $builder->groupEnd();
        }

        $rows = $builder->get()->getResultArray();
        $count = count($rows);
        if ($verbose) {
            CLI::write("Found $count expired rental(s) to notify.");
        }

        $notifikasiModel = new \App\Models\NotifikasiModel();
        $pembayaranModel = new \App\Models\PembayaranModel();

        foreach ($rows as $r) {
            try {
                $userId = $r['user_id'] ?? null;
                if (empty($userId) && !empty($r['riwayat_penawaran_id'])) {
                    $rm = new \App\Models\RiwayatPenawaranModel();
                    $rr = $rm->find($r['riwayat_penawaran_id']);
                    $userId = $rr['user_id'] ?? $userId;
                }

                $alatName = $r['nama_alat'] ?? null;
                if (empty($alatName) && !empty($r['riwayat_penawaran_id'])) {
                    $db2 = \Config\Database::connect();
                    $row = $db2->table('riwayat_penawaran rp')
                        ->select('ab.nama AS alat_nama')
                        ->join('alat_berat ab', 'ab.id = rp.alat_berat_id', 'left')
                        ->where('rp.id', $r['riwayat_penawaran_id'])
                        ->get()
                        ->getRowArray();
                    $alatName = $row['alat_nama'] ?? $alatName;
                }
                $namaAlat = $alatName ?? 'Alat Berat';

                $pesan = 'Notifikasi: Durasi sewa untuk <b>' . esc($namaAlat) . '</b> <b>telah selesai</b> pada <b>' . esc($r['rental_end_at']) . '</b>.';
                $link = base_url('alat-berat/riwayat-penawaran');

                if (!empty($userId)) {
                    $notifikasiModel->insert([
                        'user_id' => $userId,
                        'pesan' => $pesan,
                        'link' => $link,
                        'tipe' => 'pesan',
                        'is_read' => 0,
                    ]);
                }

                $update = [];
                if (in_array('rental_expiry_notified', $fields)) {
                    $update['rental_expiry_notified'] = 1;
                }
                if (in_array('show_in_durasi', $fields)) {
                    $update['show_in_durasi'] = 0;
                }
                if (!empty($update)) {
                    $pembayaranModel->update($r['id'], $update);
                }

                if ($verbose) {
                    CLI::write("Notified user={$userId} for pembayaran id={$r['id']}");
                }
            } catch (\Throwable $e) {
                if ($verbose) {
                    CLI::error('Error notifying for pembayaran id=' . ($r['id'] ?? 'unknown') . ': ' . $e->getMessage());
                } else {
                    \Config\Services::logger()->error('Error notifying for pembayaran id=' . ($r['id'] ?? 'unknown') . ': ' . $e->getMessage());
                }
            }
        }

        if ($verbose) {
            CLI::write('Done.');
        }

        return $count;
    }
}
