<?php

namespace Database\Seeders;

use App\Models\Faskes;
use App\Models\Titik;
use App\Models\Wilayah;
use Illuminate\Database\Seeder;

class WilayahSeeder extends Seeder
{
    public function run(): void
    {
        $taman = Wilayah::updateOrCreate(
            [
                'nama' => 'Kecamatan Taman',
                'kota' => 'Kota Madiun',
                'provinsi' => 'Jawa Timur',
            ],
            [
                'nama' => 'Kecamatan Taman',
                'kota' => 'Kota Madiun',
                'provinsi' => 'Jawa Timur',
            ],
        );

        Faskes::whereNull('wilayah_id')->update(['wilayah_id' => $taman->id]);
        Titik::whereNull('wilayah_id')->update(['wilayah_id' => $taman->id]);
    }
}