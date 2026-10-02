<?php

namespace Database\Seeders;

use App\Models\Faskes;
use App\Models\Jalur;
use App\Models\Titik;
use App\Models\Wilayah;
use Illuminate\Database\Seeder;

class GraphSeeder extends Seeder
{
    private const EARTH_RADIUS_M = 6371000;

    /**
     * Jaring jalan berupa grid di sekitar Kecamatan Taman, Kota Madiun.
     */
    private const GRID_LAT = [-7.616, -7.622, -7.628, -7.634, -7.640, -7.646];

    private const GRID_LNG = [111.505, 111.511, 111.517, 111.523, 111.529];

    public function run(): void
    {
        $this->seedRoadNodes();
        $this->seedFaskesNodes();
        $this->backfillFaskesNodeId();

        $tamanId = Wilayah::where('nama', 'Kecamatan Taman')->value('id');

        if ($tamanId) {
            Titik::query()->update(['wilayah_id' => $tamanId]);
        }
    }

    private function seedRoadNodes(): void
    {
        $nodes = [];
        $index = 1;

        foreach (self::GRID_LAT as $row => $lat) {
            foreach (self::GRID_LNG as $col => $lng) {
                $nodes[$row][$col] = Titik::firstOrCreate(
                    ['kode' => 'J'.$index],
                    [
                        'nama' => 'Jalan Utama - J'.$index,
                        'kode' => 'J'.$index,
                        'latitude' => $lat,
                        'longitude' => $lng,
                        'tipe' => 'jalan',
                    ],
                );
                $index++;
            }
        }

        foreach (self::GRID_LAT as $row => $lat) {
            foreach (self::GRID_LNG as $col => $lng) {
                $current = $nodes[$row][$col];

                if (isset($nodes[$row][$col + 1])) {
                    $this->createEdge($current, $nodes[$row][$col + 1], 'Jalan Lokal');
                }

                if (isset($nodes[$row + 1][$col])) {
                    $this->createEdge($current, $nodes[$row + 1][$col], 'Jalan Lokal');
                }
            }
        }
    }

    private function seedFaskesNodes(): void
    {
        Faskes::query()->get()->each(function (Faskes $faskes) {
            $titik = Titik::firstOrCreate(
                ['faskes_id' => $faskes->id],
                [
                    'nama' => $faskes->nama,
                    'kode' => 'F'.$faskes->id,
                    'latitude' => (float) $faskes->latitude,
                    'longitude' => (float) $faskes->longitude,
                    'tipe' => 'faskes',
                    'faskes_id' => $faskes->id,
                ],
            );

            $nearest = $this->nearestRoadTitik((float) $titik->latitude, (float) $titik->longitude);

            if ($nearest !== null) {
                $this->createEdge($titik, $nearest, 'Jalan Masuk / Gang');
            }
        });
    }

    private function backfillFaskesNodeId(): void
    {
        Faskes::query()->whereNull('node_id')->get()->each(function (Faskes $faskes) {
            $nearest = $this->nearestRoadTitik((float) $faskes->latitude, (float) $faskes->longitude);

            if ($nearest !== null) {
                $faskes->update(['node_id' => $nearest->id]);
            }
        });
    }

    private function nearestRoadTitik(float $lat, float $lng): ?Titik
    {
        $nearest = null;
        $nearestDist = INF;

        Titik::where('tipe', 'jalan')->get()->each(function (Titik $titik) use ($lat, $lng, &$nearest, &$nearestDist) {
            $dist = $this->haversine($lat, $lng, (float) $titik->latitude, (float) $titik->longitude);
            if ($dist < $nearestDist) {
                $nearestDist = $dist;
                $nearest = $titik;
            }
        });

        return $nearest;
    }

    private function createEdge(Titik $asal, Titik $tujuan, string $namaJalan): void
    {
        $jarak = (int) round($this->haversine(
            (float) $asal->latitude,
            (float) $asal->longitude,
            (float) $tujuan->latitude,
            (float) $tujuan->longitude,
        ));

        Jalur::firstOrCreate(
            [
                'node_awal' => $asal->id,
                'node_tujuan' => $tujuan->id,
            ],
            [
                'node_awal' => $asal->id,
                'node_tujuan' => $tujuan->id,
                'jarak' => max($jarak, 1),
                'nama_jalan' => $namaJalan,
            ],
        );

        Jalur::firstOrCreate(
            [
                'node_awal' => $tujuan->id,
                'node_tujuan' => $asal->id,
            ],
            [
                'node_awal' => $tujuan->id,
                'node_tujuan' => $asal->id,
                'jarak' => max($jarak, 1),
                'nama_jalan' => $namaJalan,
            ],
        );
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