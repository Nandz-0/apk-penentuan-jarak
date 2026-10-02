<?php

namespace Database\Seeders;

use App\Models\Faskes;
use App\Models\Jalur;
use App\Models\KategoriFaskes;
use App\Models\Titik;
use Illuminate\Database\Seeder;

/**
 * ============================================================================
 *  SEEDER DATA CONTOH (DUMMY) KHUSUS PENGUJIAN
 * ============================================================================
 *
 *  PERHATIAN:
 *  SELURUH data pada seeder ini adalah DATA CONTOH / DUMMY.
 *  Data ini BUKAN data penelitian asli dan hanya diperuntukkan bagi
 *  keperluan testing (uji coba fitur, demonstrasi, atau saat data asli
 *  belum tersedia). Jangan dipakai sebagai data penelitian final.
 *
 *  Cara menjalankan:
 *      php artisan db:seed --class=TestingSeeder
 *
 *  Penanda data contoh (agar mudah dibedakan dari data asli):
 *      - Node jalan  : kode "T1" .. "T10"
 *      - Node faskes : kode "TF1" .. "TF5"
 *      - Faskes      : nama diawali "Contoh ..."
 *      - Kategori    : "Faskes Contoh"
 *
 *  Cara menghapus kembali data contoh:
 *      php artisan tinker
 *      >>> \App\Models\Faskes::where('nama', 'like', 'Contoh %')->get()->each->delete();
 *      >>> \App\Models\Titik::where('kode', 'like', 'T%')->delete();
 *      >>> \App\Models\KategoriFaskes::where('nama', 'Faskes Contoh')->delete();
 *
 *  Seeder ini idempoten (memakai firstOrCreate/updateOrCreate), sehingga
 *  aman dijalankan berulang kali tanpa menggandakan data.
 * ============================================================================
 */
class TestingSeeder extends Seeder
{
    private const EARTH_RADIUS_M = 6371000;

    /**
     * DATA CONTOH: 10 node jalan (dummy).
     * Format: 'kode' => [latitude, longitude]
     */
    private const ROAD_NODES = [
        'T1' => [-7.6300, 111.5100],
        'T2' => [-7.6300, 111.5160],
        'T3' => [-7.6300, 111.5220],
        'T4' => [-7.6360, 111.5100],
        'T5' => [-7.6360, 111.5160],
        'T6' => [-7.6360, 111.5220],
        'T7' => [-7.6420, 111.5100],
        'T8' => [-7.6420, 111.5160],
        'T9' => [-7.6420, 111.5220],
        'T10' => [-7.6480, 111.5160],
    ];

    /**
     * DATA CONTOH: 13 relasi edge antar node jalan (dummy).
     * Setiap pasangan dibuat dua arah (node_awal <-> node_tujuan),
     * sehingga menghasilkan 26 baris edge. Bila ditambah edge penghubung
     * faskes, total edge contoh lebih dari 30 (memenuhi minimal 15 edge).
     */
    private const ROAD_EDGES = [
        ['T1', 'T2'],
        ['T2', 'T3'],
        ['T4', 'T5'],
        ['T5', 'T6'],
        ['T7', 'T8'],
        ['T8', 'T9'],
        ['T10', 'T8'],
        ['T1', 'T4'],
        ['T4', 'T7'],
        ['T2', 'T5'],
        ['T5', 'T8'],
        ['T3', 'T6'],
        ['T6', 'T9'],
    ];

    /**
     * DATA CONTOH: 5 faskes (dummy) beserta node faskes dan
     * node jalan terdekat yang dihubungkan.
     */
    private const FASKES = [
        [
            'nama' => 'Contoh Rumah Sakit Sehat',
            'alamat' => 'Jl. Contoh No. 1 (data dummy)',
            'telepon' => '0351-000001',
            'jam_operasional' => '24 Jam',
            'deskripsi' => 'Data contoh untuk pengujian, bukan faskes sebenarnya.',
            'latitude' => -7.6302,
            'longitude' => 111.5225,
            'node' => 'T3',
        ],
        [
            'nama' => 'Contoh Puskesmas Aman',
            'alamat' => 'Jl. Contoh No. 2 (data dummy)',
            'telepon' => '0351-000002',
            'jam_operasional' => '08.00 - 16.00',
            'deskripsi' => 'Data contoh untuk pengujian, bukan faskes sebenarnya.',
            'latitude' => -7.6298,
            'longitude' => 111.5095,
            'node' => 'T1',
        ],
        [
            'nama' => 'Contoh Klinik Bunda',
            'alamat' => 'Jl. Contoh No. 3 (data dummy)',
            'telepon' => '0351-000003',
            'jam_operasional' => '08.00 - 20.00',
            'deskripsi' => 'Data contoh untuk pengujian, bukan faskes sebenarnya.',
            'latitude' => -7.6361,
            'longitude' => 111.5162,
            'node' => 'T5',
        ],
        [
            'nama' => 'Contoh Apotek Sehat',
            'alamat' => 'Jl. Contoh No. 4 (data dummy)',
            'telepon' => '0351-000004',
            'jam_operasional' => '07.00 - 22.00',
            'deskripsi' => 'Data contoh untuk pengujian, bukan faskes sebenarnya.',
            'latitude' => -7.6419,
            'longitude' => 111.5163,
            'node' => 'T8',
        ],
        [
            'nama' => 'Contoh Rumah Sakit Khusus',
            'alamat' => 'Jl. Contoh No. 5 (data dummy)',
            'telepon' => '0351-000005',
            'jam_operasional' => '24 Jam',
            'deskripsi' => 'Data contoh untuk pengujian, bukan faskes sebenarnya.',
            'latitude' => -7.6479,
            'longitude' => 111.5158,
            'node' => 'T10',
        ],
    ];

    public function run(): void
    {
        // 1) Kategori faskes contoh (dummy), dipakai oleh seluruh faskes contoh.
        $kategori = KategoriFaskes::firstOrCreate(
            ['nama' => 'Faskes Contoh'],
            [
                'nama' => 'Faskes Contoh',
                'deskripsi' => 'Kategori contoh untuk pengujian (data dummy).',
                'warna' => '#6c757d',
            ],
        );

        // 2) DATA CONTOH: 10 node jalan.
        $nodes = [];

        foreach (self::ROAD_NODES as $kode => [$latitude, $longitude]) {
            $nodes[$kode] = Titik::firstOrCreate(
                ['kode' => $kode],
                [
                    'nama' => 'Node Contoh '.$kode,
                    'kode' => $kode,
                    'latitude' => $latitude,
                    'longitude' => $longitude,
                    'tipe' => 'jalan',
                ],
            );
        }

        // 3) DATA CONTOH: edge antar node jalan (dua arah).
        foreach (self::ROAD_EDGES as [$awal, $tujuan]) {
            $this->createEdge($nodes[$awal], $nodes[$tujuan], 'Jalan Contoh');
        }

        // 4) DATA CONTOH: 5 faskes + node faskes + edge penghubung ke node jalan.
        foreach (self::FASKES as $index => $data) {
            $faskes = Faskes::updateOrCreate(
                ['nama' => $data['nama']],
                [
                    'kategori_faskes_id' => $kategori->id,
                    'wilayah_id' => null,
                    'nama' => $data['nama'],
                    'alamat' => $data['alamat'],
                    'telepon' => $data['telepon'],
                    'jam_operasional' => $data['jam_operasional'],
                    'deskripsi' => $data['deskripsi'],
                    'latitude' => $data['latitude'],
                    'longitude' => $data['longitude'],
                ],
            );

            // Hubungkan faskes ke node jalan terdekat (data contoh).
            $jalan = $nodes[$data['node']];
            $faskes->update(['node_id' => $jalan->id]);

            // Node representasi faskes pada graf (data contoh).
            $nodeFaskes = Titik::firstOrCreate(
                ['faskes_id' => $faskes->id],
                [
                    'nama' => $faskes->nama,
                    'kode' => 'TF'.($index + 1),
                    'latitude' => $faskes->latitude,
                    'longitude' => $faskes->longitude,
                    'tipe' => 'faskes',
                    'faskes_id' => $faskes->id,
                ],
            );

            // Edge penghubung (dua arah) antara node faskes dan node jalan.
            $this->createEdge($nodeFaskes, $jalan, 'Jalan Masuk Contoh');
        }

        // Ringkasan hasil seeding (data contoh).
        $this->command?->info('TestingSeeder: data CONTOH (dummy) berhasil dibuat.');
        $this->command?->info('  Node jalan contoh : '.count(self::ROAD_NODES).' (kode T1-T10)');
        $this->command?->info('  Faskes contoh     : '.count(self::FASKES).' (nama diawali "Contoh")');
        $this->command?->info('  Kategori contoh   : 1 (Faskes Contoh)');
    }

    /**
     * Membuat edge dua arah (data contoh / dummy) dengan jarak hasil
     * perhitungan haversine antara kedua node.
     */
    private function createEdge(Titik $asal, Titik $tujuan, string $namaJalan): void
    {
        $jarak = max((int) round($this->haversine(
            (float) $asal->latitude,
            (float) $asal->longitude,
            (float) $tujuan->latitude,
            (float) $tujuan->longitude,
        )), 1);

        foreach ([[$asal, $tujuan], [$tujuan, $asal]] as [$a, $b]) {
            Jalur::firstOrCreate(
                ['node_awal' => $a->id, 'node_tujuan' => $b->id],
                ['node_awal' => $a->id, 'node_tujuan' => $b->id, 'jarak' => $jarak, 'nama_jalan' => $namaJalan],
            );
        }
    }

    private function haversine(float $lat1, float $lng1, float $lat2, float $lng2): float
    {
        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);

        $a = sin($dLat / 2) ** 2
            + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLng / 2) ** 2;
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return self::EARTH_RADIUS_M * $c;
    }
}
