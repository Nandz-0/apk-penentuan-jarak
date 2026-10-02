<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\EdgeRequest;
use App\Models\Jalur;
use App\Models\Titik;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class JalurController extends Controller
{
    public function index(): View
    {
        return view('admin.edges.index', [
            'jalur' => Jalur::with('nodeAwal', 'nodeTujuan')->paginate(15),
        ]);
    }

    public function create(): View
    {
        return view('admin.edges.create', [
            'titikList' => Titik::orderBy('tipe')->orderBy('nama')->get(),
        ]);
    }

    public function store(EdgeRequest $request): RedirectResponse
    {
        $data = $request->validated();

        Jalur::firstOrCreate(
            ['node_awal' => $data['node_awal'], 'node_tujuan' => $data['node_tujuan']],
            $data,
        );

        return redirect()->route('admin.edges.index')->with('success', 'Jalur berhasil ditambahkan.');
    }

    public function edit(Jalur $jalur): View
    {
        return view('admin.edges.edit', [
            'jalur' => $jalur,
            'titikList' => Titik::orderBy('tipe')->orderBy('nama')->get(),
        ]);
    }

    public function update(EdgeRequest $request, Jalur $jalur): RedirectResponse
    {
        $jalur->update($request->validated());

        return redirect()->route('admin.edges.index')->with('success', 'Jalur berhasil diperbarui.');
    }

    public function destroy(Jalur $jalur): RedirectResponse
    {
        $reverse = Jalur::where('node_awal', $jalur->node_tujuan)
            ->where('node_tujuan', $jalur->node_awal)
            ->first();

        if ($reverse) {
            $reverse->delete();
        }

        $jalur->delete();

        return redirect()->route('admin.edges.index')->with('success', 'Jalur berhasil dihapus.');
    }
}
