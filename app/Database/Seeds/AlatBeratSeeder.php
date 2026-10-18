<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AlatBeratSeeder extends Seeder
{
    public function run()
    {
        $data = [
            // Excavator
            [
                'nama' => 'Excavator Komatsu PC200',
                'jenis' => 'Excavator',
                'kapasitas' => 1.0,
                'status' => 'tersedia',
                'deskripsi' => 'Excavator Komatsu PC200 dengan bucket standar. Cocok untuk penggalian tanah, pembuatan parit, dan pekerjaan konstruksi umum.',
                'gambar' => 'excavator-pc200.jpg',
                'harga_sewa' => 2500000,
                'jenis' => 'excavator'
            ],
            [
                'nama' => 'Excavator Hitachi ZX200',
                'jenis' => 'Excavator',
                'kapasitas' => 1.0,
                'status' => 'tersedia',
                'deskripsi' => 'Excavator Hitachi ZX200 dengan performa tinggi dan efisiensi bahan bakar yang baik.',
                'gambar' => 'excavator-zx200.jpg',
                'harga_sewa' => 2300000,
                'jenis' => 'excavator'
            ],

            // Vibro Roller
            [
                'nama' => 'Vibro Roller Sakai SV512D',
                'jenis' => 'Vibro Roller',
                'kapasitas' => 12,
                'status' => 'tersedia',
                'deskripsi' => 'Vibro Roller Sakai SV512D untuk pemadatan tanah dan aspal dengan getaran yang optimal.',
                'gambar' => 'vibro-sakai.jpg',
                'harga_sewa' => 1500000,
                'jenis' => 'vibro-roller'
            ],
            [
                'nama' => 'Vibro Roller Hamm HD110',
                'jenis' => 'Vibro Roller',
                'kapasitas' => 11,
                'status' => 'tersedia',
                'deskripsi' => 'Vibro Roller Hamm HD110 dengan sistem getaran yang kuat untuk hasil pemadatan maksimal.',
                'gambar' => 'vibro-hamm.jpg',
                'harga_sewa' => 1400000,
                'jenis' => 'vibro-roller'
            ],

            // Dozer
            [
                'nama' => 'Bulldozer Komatsu D65EX',
                'jenis' => 'Dozer',
                'kapasitas' => 20,
                'status' => 'tersedia',
                'deskripsi' => 'Bulldozer Komatsu D65EX dengan tenaga besar untuk pekerjaan berat dan medan yang sulit.',
                'gambar' => 'dozer-komatsu.jpg',
                'harga_sewa' => 3000000,
                'jenis' => 'dozer'
            ],
            [
                'nama' => 'Bulldozer Caterpillar D6R',
                'jenis' => 'Dozer',
                'kapasitas' => 18,
                'status' => 'tersedia',
                'deskripsi' => 'Bulldozer Caterpillar D6R dengan kontrol yang presisi dan efisiensi bahan bakar yang baik.',
                'gambar' => 'dozer-cat.jpg',
                'harga_sewa' => 2800000,
                'jenis' => 'dozer'
            ],

            // Motor Grader
            [
                'nama' => 'Motor Grader Komatsu GD655',
                'jenis' => 'Motor Grader',
                'kapasitas' => 15,
                'status' => 'tersedia',
                'deskripsi' => 'Motor Grader Komatsu GD655 untuk perataan permukaan tanah dan pembentukan kemiringan yang presisi.',
                'gambar' => 'grader-komatsu.jpg',
                'harga_sewa' => 2200000,
                'jenis' => 'motor-grader'
            ],
            [
                'nama' => 'Motor Grader Caterpillar 120K',
                'jenis' => 'Motor Grader',
                'kapasitas' => 14,
                'status' => 'tersedia',
                'deskripsi' => 'Motor Grader Caterpillar 120K dengan sistem kontrol yang canggih untuk hasil perataan yang sempurna.',
                'gambar' => 'grader-cat.jpg',
                'harga_sewa' => 2000000,
                'jenis' => 'motor-grader'
            ],

            // Wheel Loader
            [
                'nama' => 'Wheel Loader Komatsu WA380',
                'jenis' => 'Wheel Loader',
                'kapasitas' => 3.8,
                'status' => 'tersedia',
                'deskripsi' => 'Wheel Loader Komatsu WA380 untuk memindahkan material dengan kapasitas bucket yang besar.',
                'gambar' => 'loader-komatsu.jpg',
                'harga_sewa' => 1800000,
                'jenis' => 'wheel-loader'
            ],
            [
                'nama' => 'Wheel Loader Volvo L120',
                'jenis' => 'Wheel Loader',
                'kapasitas' => 3.5,
                'status' => 'tersedia',
                'deskripsi' => 'Wheel Loader Volvo L120 dengan kenyamanan operator dan efisiensi bahan bakar yang tinggi.',
                'gambar' => 'loader-volvo.jpg',
                'harga_sewa' => 1700000,
                'jenis' => 'wheel-loader'
            ],

            // Backhoe Loader
            [
                'nama' => 'Backhoe Loader JCB 3CX',
                'jenis' => 'Backhoe Loader',
                'kapasitas' => 1.0,
                'status' => 'tersedia',
                'deskripsi' => 'Backhoe Loader JCB 3CX dengan kemampuan multi-fungsi untuk menggali dan memindahkan material.',
                'gambar' => 'backhoe-jcb.jpg',
                'harga_sewa' => 1600000,
                'jenis' => 'backhoe-loader'
            ],
            [
                'nama' => 'Backhoe Loader Caterpillar 420F',
                'jenis' => 'Backhoe Loader',
                'kapasitas' => 1.2,
                'status' => 'tersedia',
                'deskripsi' => 'Backhoe Loader Caterpillar 420F dengan tenaga besar dan kontrol yang presisi.',
                'gambar' => 'backhoe-cat.jpg',
                'harga_sewa' => 1800000,
                'jenis' => 'backhoe-loader'
            ],

            // Dump Truck
            [
                'nama' => 'Dump Truck Hino FM260',
                'jenis' => 'Dump Truck',
                'kapasitas' => 8,
                'status' => 'tersedia',
                'deskripsi' => 'Dump Truck Hino FM260 dengan kapasitas angkut yang besar dan efisiensi bahan bakar yang baik.',
                'gambar' => 'dumptruck-hino.jpg',
                'harga_sewa' => 1200000,
                'jenis' => 'dump-truck'
            ],
            [
                'nama' => 'Dump Truck Mitsubishi Colt Diesel',
                'jenis' => 'Dump Truck',
                'kapasitas' => 7,
                'status' => 'tersedia',
                'deskripsi' => 'Dump Truck Mitsubishi Colt Diesel dengan performa yang handal untuk pengangkutan material.',
                'gambar' => 'dumptruck-mitsubishi.jpg',
                'harga_sewa' => 1100000,
                'jenis' => 'dump-truck'
            ]
        ];

        $this->db->table('alat_berat')->insertBatch($data);
    }
} 