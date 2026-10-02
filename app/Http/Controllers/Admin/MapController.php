<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faskes;
use App\Models\Jalur;
use App\Models\Titik;
use Illuminate\View\View;

class MapController extends Controller
{
    public function index(): View
    {
        return view('admin.map', [
            'nodes' => Titik::with('faskesInduk')->orderBy('tipe')->orderBy('nama')->get(),
            'edges' => Jalur::with('nodeAwal', 'nodeTujuan')->get(),
            'faskesCount' => Faskes::count(),
        ]);
    }
}