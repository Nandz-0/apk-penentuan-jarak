<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\FaskesRequest;
use App\Models\Faskes;
use App\Models\KategoriFaskes;
use App\Models\Titik;
use App\Models\Wilayah;
use App\Services\DijkstraService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class FaskesController extends Controller
{
    public function index(): View
    {
        return view('admin.faskes.index', [
            'faskes' => Faskes::with('kategori', 'wilayah', 'node')->orderBy('nama')->paginate(10),
        ]);
    }

    public function show(Faskes $faskes): View
    {
        return view('admin.faskes.show', [
            'faskes' => $faskes,
        ]);
    }

    public function create(): View
    {
        return view('admin.faskes.create', [
            'kategoris' => KategoriFaskes::orderBy('nama')->get(),
            'wilayahs' => Wilayah::orderBy('nama')->get(),
            'nodeList' => $this->nodeList(),
        ]);
    }

    public function store(FaskesRequest $request, DijkstraService $service): RedirectResponse
    {
        $data = $request->validated();

        if ($request->filled('node_id')) {
            $data['node_id'] = (int) $request->input('node_id');
        } else {
            $data['node_id'] = $this->suggestNodeId((float) $data['latitude'], (float) $data['longitude'], $service);
        }

        $faskes = Faskes::create($data);

        $this->syncTitik($faskes);

        return redirect()->route('admin.faskes.index')->with('success', 'Data faskes berhasil ditambahkan.');
    }

    public function edit(Faskes $faskes): View
    {
        return view('admin.faskes.edit', [
            'faskes' => $faskes,
            'kategoris' => KategoriFaskes::orderBy('nama')->get(),
            'wilayahs' => Wilayah::orderBy('nama')->get(),
            'nodeList' => $this->nodeList(),
        ]);
    }

    public function update(FaskesRequest $request, Faskes $faskes, DijkstraService $service): RedirectResponse
    {
        $data = $request->validated();

        if (! $request->filled('node_id')) {
            $data['node_id'] = $this->suggestNodeId((float) $data['latitude'], (float) $data['longitude'], $service);
        }

        $faskes->update($data);

        $this->syncTitik($faskes);

        return redirect()->route('admin.faskes.index')->with('success', 'Data faskes berhasil diperbarui.');
    }

    public function destroy(Faskes $faskes): RedirectResponse
    {
        Titik::where('faskes_id', $faskes->id)->delete();
        $faskes->delete();

        return redirect()->route('admin.faskes.index')->with('success', 'Data faskes berhasil dihapus.');
    }

    private function nodeList(): array
    {
        return Titik::with('faskesInduk')->orderBy('tipe')->orderBy('nama')->get()->all();
    }

    private function suggestNodeId(float $latitude, float $longitude, DijkstraService $service): ?int
    {
        $nearest = $service->findNearestNode($latitude, $longitude, 'jalan');

        return $nearest?->id;
    }

    private function syncTitik(Faskes $faskes): void
    {
        $titik = Titik::firstOrNew(['faskes_id' => $faskes->id]);

        $titik->fill([
            'nama' => $faskes->nama,
            'kode' => 'F'.$faskes->id,
            'latitude' => $faskes->latitude,
            'longitude' => $faskes->longitude,
            'tipe' => 'faskes',
            'faskes_id' => $faskes->id,
            'wilayah_id' => $faskes->wilayah_id,
        ])->save();
    }
}