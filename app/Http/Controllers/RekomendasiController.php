<?php

namespace App\Http\Controllers;

use App\Services\RekomendasiService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RekomendasiController extends Controller
{
    public function cari(Request $request, RekomendasiService $service): JsonResponse
    {
        $request->validate([
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'kategori_faskes_id' => ['nullable', 'integer', 'exists:kategori_faskes,id'],
        ]);

        $result = $service->rekomendasi(
            (float) $request->input('latitude'),
            (float) $request->input('longitude'),
            [
                'kategori_faskes_id' => $request->integer('kategori_faskes_id') ?: null,
            ],
        );

        if ($result === null) {
            return response()->json([
                'message' => 'Tidak ada titik jalan pada database. Tambahkan data titik dan jalur terlebih dahulu.',
                'results' => [],
            ], 422);
        }

        // Tidak ada faskes sama sekali untuk kategori/filter yang dipilih.
        if ($result['candidate_total'] === 0) {
            return response()->json([
                'message' => 'Belum tersedia data faskes untuk kategori ini.',
                'results' => [],
            ]);
        }

        // Ada faskes, tetapi setiap faskes tidak dapat dijangkau dari lokasi awal.
        if (count($result['results']) === 0) {
            return response()->json([
                'message' => 'Maaf, rute menuju faskes tidak ditemukan pada jaringan jalan yang tersedia.',
                'results' => [],
            ]);
        }

        return response()->json($this->format($result));
    }

    /**
     * @param  array<string, mixed>  $result
     * @return array<string, mixed>
     */
    private function format(array $result): array
    {
        return [
            'start' => $result['start'],
            'start_titik' => [
                'id' => $result['start_titik']->id,
                'nama' => $result['start_titik']->nama,
                'latitude' => (float) $result['start_titik']->latitude,
                'longitude' => (float) $result['start_titik']->longitude,
            ],
            'count' => count($result['results']),
            'computed_at' => $result['computed_at']->toIso8601String(),
            'results' => collect($result['results'])->map(function (array $item) {
                return [
                    'faskes' => [
                        'id' => $item['faskes']->id,
                        'nama' => $item['faskes']->nama,
                        'alamat' => $item['faskes']->alamat,
                        'telepon' => $item['faskes']->telepon,
                        'jam_operasional' => $item['faskes']->jam_operasional,
                        'deskripsi' => $item['faskes']->deskripsi,
                        'latitude' => (float) $item['faskes']->latitude,
                        'longitude' => (float) $item['faskes']->longitude,
                        'kategori' => [
                            'id' => $item['faskes']->kategori->id,
                            'nama' => $item['faskes']->kategori->nama,
                            'warna' => $item['faskes']->kategori->warna,
                        ],
                    ],
                    'total_distance_m' => round($item['total_distance'], 2),
                    'distance_graph_m' => round($item['distance_graph'], 2),
                    'distance_entry_m' => round($item['distance_entry'], 2),
                    'path' => $item['path'],
                ];
            })->all(),
        ];
    }
}