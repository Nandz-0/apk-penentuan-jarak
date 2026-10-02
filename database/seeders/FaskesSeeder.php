<?php

namespace Database\Seeders;

use App\Models\Faskes;
use App\Models\KategoriFaskes;
use App\Models\Wilayah;
use Illuminate\Database\Seeder;

class FaskesSeeder extends Seeder
{
    public function run(): void
    {
        $kategoriPuskesmas = KategoriFaskes::where('nama', 'Puskesmas')->value('id');
        $kategoriRs = KategoriFaskes::where('nama', 'Rumah Sakit Umum')->value('id');
        $kategoriKlinik = KategoriFaskes::where('nama', 'Klinik')->value('id');
        $kategoriApotek = KategoriFaskes::where('nama', 'Apotek')->value('id');

        $faskes = [
            [
                'kategori_faskes_id' => $kategoriPuskesmas,
                'nama' => 'Puskesmas Banjarejo',
                'alamat' => 'Jl. Bhayangkara No. 1, Banjarejo, Kec. Taman, Kota Madiun',
                'telepon' => '(0351) 456322',
                'jam_operasional' => 'Senin - Jumat, 07.00 - 14.00',
                'deskripsi' => 'Puskesmas rawat jalan wilayah Kecamatan Taman.',
                'latitude' => -7.6275,
                'longitude' => 111.5225,
            ],
            [
                'kategori_faskes_id' => $kategoriPuskesmas,
                'nama' => 'Puskesmas Demangan',
                'alamat' => 'Jl. Sukarno-Hatta No. 47, Demangan, Kec. Taman, Kota Madiun',
                'telepon' => '(0351) 464308',
                'jam_operasional' => 'Senin - Jumat, 07.00 - 14.00',
                'deskripsi' => 'UPTD Puskesmas Demangan dengan layanan rawat jalan.',
                'latitude' => -7.6169,
                'longitude' => 111.5175,
            ],
            [
                'kategori_faskes_id' => $kategoriPuskesmas,
                'nama' => 'Puskesmas Pembantu Taman',
                'alamat' => 'Jl. Serayu, Pandean, Kec. Taman, Kota Madiun',
                'telepon' => '(0351) 466000',
                'jam_operasional' => 'Senin - Jumat, 07.00 - 14.00',
                'deskripsi' => 'Puskesmas pembantu yang melayani kelurahan Pandean dan sekitarnya.',
                'latitude' => -7.6375,
                'longitude' => 111.5070,
            ],
            [
                'kategori_faskes_id' => $kategoriRs,
                'nama' => 'RS Griya Husada',
                'alamat' => 'Jl. Mayjend Panjaitan No. 22, Banjarejo, Kec. Taman, Kota Madiun',
                'telepon' => '(0351) 472801',
                'jam_operasional' => '24 Jam (IGD)',
                'deskripsi' => 'Rumah sakit swasta tipe C dengan layanan rawat jalan dan rawat inap.',
                'latitude' => -7.6235,
                'longitude' => 111.5290,
            ],
            [
                'kategori_faskes_id' => $kategoriKlinik,
                'nama' => 'Klinik Telkomedika',
                'alamat' => 'Jl. Manggis No. 9, Kec. Taman, Kota Madiun',
                'telepon' => '(0351) 476697',
                'jam_operasional' => 'Senin - Sabtu, 08.00 - 20.00',
                'deskripsi' => 'Klinik pratama dengan pelayanan umum dan penunjang diagnostik.',
                'latitude' => -7.6280,
                'longitude' => 111.5115,
            ],
            [
                'kategori_faskes_id' => $kategoriKlinik,
                'nama' => 'Klinik Juwita Medika',
                'alamat' => 'Jl. Kapten Saputra No. 55, Kec. Taman, Kota Madiun',
                'telepon' => '(0351) 000000',
                'jam_operasional' => 'Senin - Sabtu, 08.00 - 20.00',
                'deskripsi' => 'Klinik pratama dengan layanan dokter umum.',
                'latitude' => -7.6295,
                'longitude' => 111.5145,
            ],
            [
                'kategori_faskes_id' => $kategoriKlinik,
                'nama' => 'Klinik Twin Medica',
                'alamat' => 'Jl. Kelapa Manis No. 35E, Kec. Taman, Kota Madiun',
                'telepon' => '(0351) 000000',
                'jam_operasional' => 'Senin - Sabtu, 08.00 - 20.00',
                'deskripsi' => 'Klinik pratama dengan layanan dokter umum dan gigi.',
                'latitude' => -7.6345,
                'longitude' => 111.5175,
            ],
            [
                'kategori_faskes_id' => $kategoriKlinik,
                'nama' => 'Klinik Dua Empat',
                'alamat' => 'Jl. Dungus Manis No. 22A, Kec. Taman, Kota Madiun',
                'telepon' => '(0351) 000000',
                'jam_operasional' => 'Senin - Sabtu, 08.00 - 21.00',
                'deskripsi' => 'Klinik pratama dengan pelayanan umum.',
                'latitude' => -7.6415,
                'longitude' => 111.5120,
            ],
            [
                'kategori_faskes_id' => $kategoriRs,
                'nama' => 'RSUD Kota Madiun',
                'alamat' => 'Jl. Campursari No. 12B, Sogaten, Kota Madiun',
                'telepon' => '(0351) 481314',
                'jam_operasional' => '24 Jam (IGD)',
                'deskripsi' => 'Rumah Sakit Umum Daerah tipe C milik Pemerintah Kota Madiun.',
                'latitude' => -7.6218,
                'longitude' => 111.5295,
            ],
            [
                'kategori_faskes_id' => $kategoriRs,
                'nama' => 'RS Santa Clara',
                'alamat' => 'Jl. Biliton No. 15, Kota Madiun',
                'telepon' => '(0351) 462150',
                'jam_operasional' => '24 Jam (IGD)',
                'deskripsi' => 'Rumah sakit umum swasta tipe C dengan layanan lengkap.',
                'latitude' => -7.6288,
                'longitude' => 111.5285,
            ],
            [
                'kategori_faskes_id' => $kategoriApotek,
                'nama' => 'Apotek K-24 Taman',
                'alamat' => 'Jl. Kapten Saputra, Kec. Taman, Kota Madiun',
                'telepon' => '(0351) 000000',
                'jam_operasional' => '24 Jam',
                'deskripsi' => 'Apotek jaringan K-24 yang buka 24 jam.',
                'latitude' => -7.6302,
                'longitude' => 111.5120,
            ],
            [
                'kategori_faskes_id' => $kategoriApotek,
                'nama' => 'Apotek Kimia Farma',
                'alamat' => 'Jl. MT Haryono, Demangan, Kec. Taman, Kota Madiun',
                'telepon' => '(0351) 453381',
                'jam_operasional' => 'Setiap hari, 08.00 - 22.00',
                'deskripsi' => 'Apotek jaringan Kimia Farma.',
                'latitude' => -7.6200,
                'longitude' => 111.5180,
            ],
        ];

        foreach ($faskes as $item) {
            Faskes::updateOrCreate(
                ['nama' => $item['nama']],
                $item,
            );
        }

        $tamanId = Wilayah::where('nama', 'Kecamatan Taman')->value('id');

        if ($tamanId) {
            Faskes::query()->update(['wilayah_id' => $tamanId]);
        }
    }
}