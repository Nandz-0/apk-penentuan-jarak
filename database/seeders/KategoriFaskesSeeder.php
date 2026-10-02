<?php

namespace Database\Seeders;

use App\Models\KategoriFaskes;
use Illuminate\Database\Seeder;

class KategoriFaskesSeeder extends Seeder
{
    public function run(): void
    {
        $kategoris = [
            ['nama' => 'Puskesmas', 'deskripsi' => 'Pusat Kesehatan Masyarakat tingkat kecamatan', 'warna' => '#198754'],
            ['nama' => 'Rumah Sakit Umum', 'deskripsi' => 'Rumah sakit yang melayani berbagai bidang kesehatan', 'warna' => '#dc3545'],
            ['nama' => 'Rumah Sakit Khusus', 'deskripsi' => 'Rumah sakit dengan pelayanan khusus', 'warna' => '#fd7e14'],
            ['nama' => 'Klinik', 'deskripsi' => 'Fasilitas pelayanan kesehatan dasar', 'warna' => '#0d6efd'],
            ['nama' => 'Apotek', 'deskripsi' => 'Tempat penjualan obat dan alat kesehatan', 'warna' => '#6f42c1'],
        ];

        foreach ($kategoris as $kategori) {
            KategoriFaskes::firstOrCreate(['nama' => $kategori['nama']], $kategori);
        }
    }
}