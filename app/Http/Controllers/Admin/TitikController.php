<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\NodeRequest;
use App\Models\Faskes;
use App\Models\Titik;
use App\Models\Wilayah;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TitikController extends Controller
{
    public function index(): View
    {
        return view('admin.nodes.index', [
            'titik' => Titik::with('faskesInduk', 'wilayah')->orderBy('tipe')->orderBy('nama')->paginate(15),
            'mapNodes' => Titik::all(),
        ]);
    }

    public function create(): View
    {
        return view('admin.nodes.create', [
            'faskesList' => Faskes::orderBy('nama')->get(),
            'wilayahs' => Wilayah::orderBy('nama')->get(),
        ]);
    }

    public function store(NodeRequest $request): RedirectResponse
    {
        Titik::create($request->validated());

        return redirect()->route('admin.nodes.index')->with('success', 'Titik berhasil ditambahkan.');
    }

    public function edit(Titik $node): View
    {
        return view('admin.nodes.edit', [
            'titik' => $node,
            'faskesList' => Faskes::orderBy('nama')->get(),
            'wilayahs' => Wilayah::orderBy('nama')->get(),
        ]);
    }

    public function update(NodeRequest $request, Titik $node): RedirectResponse
    {
        $node->update($request->validated());

        return redirect()->route('admin.nodes.index')->with('success', 'Titik berhasil diperbarui.');
    }

    public function destroy(Titik $node): RedirectResponse
    {
        $node->delete();

        return redirect()->route('admin.nodes.index')->with('success', 'Titik berhasil dihapus.');
    }
}