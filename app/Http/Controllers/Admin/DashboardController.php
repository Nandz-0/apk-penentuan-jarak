<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faskes;
use App\Models\Jalur;
use App\Models\Titik;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $perKategori = Faskes::query()
            ->join('kategori_faskes', 'faskes.kategori_faskes_id', '=', 'kategori_faskes.id')
            ->selectRaw('kategori_faskes.nama as kategori, COUNT(*) as total')
            ->groupBy('kategori_faskes.nama')
            ->pluck('total', 'kategori');

        $stats = [
            'total_faskes' => Faskes::count(),
            'total_puskesmas' => (int) ($perKategori['Puskesmas'] ?? 0),
            'total_klinik' => (int) ($perKategori['Klinik'] ?? 0),
            'total_rumah_sakit' => 0,
            'total_apotek' => (int) ($perKategori['Apotek'] ?? 0),
            'total_node' => Titik::count(),
            'total_edge' => Jalur::count(),
        ];

        foreach ($perKategori as $kategori => $total) {
            if (str_starts_with(strtolower($kategori), 'rumah sakit')) {
                $stats['total_rumah_sakit'] += (int) $total;
            }
        }

        return view('admin.dashboard', compact('stats'));
    }
}