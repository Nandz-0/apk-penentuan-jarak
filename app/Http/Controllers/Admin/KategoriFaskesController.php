<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KategoriFaskes;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class KategoriFaskesController extends Controller
{
    public function index(): View
    {
        return view('admin.kategori-faskes.index', [
            'kategoris' => KategoriFaskes::withCount('faskes')->orderBy('nama')->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.kategori-faskes.create');
    }

    public function store(Request $request): RedirectResponse
    {
        KategoriFaskes::create($this->validated($request));

        return redirect()->route('admin.kategori-faskes.index')->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function edit(KategoriFaskes $kategoriFaskes): View
    {
        return view('admin.kategori-faskes.edit', compact('kategoriFaskes'));
    }

    public function update(Request $request, KategoriFaskes $kategoriFaskes): RedirectResponse
    {
        $kategoriFaskes->update($this->validated($request));

        return redirect()->route('admin.kategori-faskes.index')->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(KategoriFaskes $kategoriFaskes): RedirectResponse
    {
        $kategoriFaskes->delete();

        return redirect()->route('admin.kategori-faskes.index')->with('success', 'Kategori berhasil dihapus.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'nama' => ['required', 'string', 'max:100', 'unique:kategori_faskes,nama'],
            'deskripsi' => ['nullable', 'string', 'max:255'],
            'warna' => ['required', 'string', 'max:20'],
        ]);
    }
}