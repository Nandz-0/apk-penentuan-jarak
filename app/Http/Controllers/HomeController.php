<?php

namespace App\Http\Controllers;

use App\Models\Faskes;
use App\Models\Jalur;
use App\Models\KategoriFaskes;
use App\Models\Titik;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        return view('home', [
            'kategoris' => KategoriFaskes::orderBy('nama')->get(),
            'faskes' => Faskes::with('kategori')->orderBy('nama')->get(),
            'titik' => Titik::orderBy('tipe')->orderBy('nama')->get(),
        ]);
    }

    public function peta(): View
    {
        return view('peta', [
            'faskes' => Faskes::with('kategori')->get(),
            'titik' => Titik::with('faskesInduk')->get(),
            'jalur' => Jalur::with('nodeAwal', 'nodeTujuan')->get(),
        ]);
    }

    public function detail(Faskes $faskes): View
    {
        $faskes->load('kategori', 'wilayah', 'titik');

        return view('faskes.detail', [
            'faskes' => $faskes,
            'jalan_terdekat' => $this->nearestRoad($faskes),
        ]);
    }

    private function nearestRoad(Faskes $faskes): ?Titik
    {
        $nearest = null;
        $nearestDist = INF;

        Titik::where('tipe', 'jalan')->get()->each(function (Titik $titik) use ($faskes, &$nearest, &$nearestDist) {
            $dist = $this->haversine(
                (float) $faskes->latitude,
                (float) $faskes->longitude,
                (float) $titik->latitude,
                (float) $titik->longitude,
            );

            if ($dist < $nearestDist) {
                $nearestDist = $dist;
                $nearest = $titik;
            }
        });

        return $nearest;
    }

    private function haversine(float $lat1, float $lng1, float $lat2, float $lng2): float
    {
        $earthRadius = 6371000;

        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);

        $a = sin($dLat / 2) ** 2
            + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLng / 2) ** 2;
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }
}