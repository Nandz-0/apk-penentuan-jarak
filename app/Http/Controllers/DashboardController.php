<?php

namespace App\Http\Controllers;

use App\Models\Faskes;
use App\Models\Jalur;
use App\Models\KategoriFaskes;
use App\Models\Titik;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $ticks = [
            'total_faskes' => Faskes::count(),
            'total_kategori' => KategoriFaskes::count(),
            'total_titik' => Titik::count(),
            'total_jalur' => Jalur::count(),
            'total_user' => User::count(),
        ];

        $faskesPerKategori = KategoriFaskes::withCount('faskes')->orderBy('faskes_count', 'desc')->get();

        return view('dashboard', compact('ticks', 'faskesPerKategori'));
    }
}