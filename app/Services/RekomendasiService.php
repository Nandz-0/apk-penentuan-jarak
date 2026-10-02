<?php

namespace App\Services;

use App\Models\Faskes;
use App\Models\Titik;

class RekomendasiService
{
    public function __construct(
        private readonly DijkstraService $dijkstra,
    ) {
    }

    /**
     * Hitung faskes terdekat menggunakan Dijkstra.
     *
     * @param  array<string, mixed>  $filters
     * @return array<string, mixed>|null
     */
    public function rekomendasi(float $startLat, float $startLng, array $filters = []): ?array
    {
        $startTitik = $this->dijkstra->findNearestNode($startLat, $startLng, 'jalan');

        if ($startTitik === null) {
            return null;
        }

        $entryDistance = $this->dijkstra->haversine(
            $startLat,
            $startLng,
            (float) $startTitik->latitude,
            (float) $startTitik->longitude,
        );

        $result = $this->dijkstra->run((int) $startTitik->id);
        $dist = $result['dist'];

        $kategoriId = $filters['kategori_faskes_id'] ?? null;

        $faskesQuery = Faskes::query()
            ->with('kategori')
            ->whereHas('titik', fn ($q) => $q->where('tipe', 'faskes'));

        if ($kategoriId) {
            $faskesQuery->where('kategori_faskes_id', $kategoriId);
        }

        $candidates = $faskesQuery->get();
        $candidateTotal = $candidates->count();

        $results = [];

        $candidates->each(function (Faskes $faskes) use ($startTitik, $entryDistance, $dist, $result, &$results) {
            $titik = $faskes->node ?? $faskes->titik;

            if ($titik === null) {
                return;
            }

            $titikId = (int) $titik->id;

            if (! isset($dist[$titikId]) || $dist[$titikId] === INF || ! is_finite($dist[$titikId])) {
                return;
            }

            $totalDistance = $entryDistance + $dist[$titikId];

            $results[] = [
                'faskes' => $faskes,
                'total_distance' => round($totalDistance, 2),
                'distance_graph' => round($dist[$titikId], 2),
                'distance_entry' => round($entryDistance, 2),
                'path' => $this->buildPathFromPrev(
                    (int) $startTitik->id,
                    $titikId,
                    $dist,
                    $result['prev'],
                ),
            ];
        });

        usort($results, fn ($a, $b) => $a['total_distance'] <=> $b['total_distance']);

        return [
            'start' => [
                'latitude' => $startLat,
                'longitude' => $startLng,
            ],
            'start_titik' => $startTitik,
            'candidate_total' => $candidateTotal,
            'results' => $results,
            'computed_at' => now(),
        ];
    }

    /**
     * Rekonstruksi lintasan terpendek dari prev hasil Dijkstra.
     *
     * @param  array<int,float>  $dist
     * @param  array<int,int|null>  $prev
     * @return array<int, array<string, mixed>>
     */
    private function buildPathFromPrev(int $startId, int $goalId, array $dist, array $prev): array
    {
        if ($startId === $goalId) {
            return [];
        }

        $path = [];
        $current = $goalId;

        while ($current !== null && $current !== $startId) {
            array_unshift($path, $current);
            $current = $prev[$current] ?? null;
        }

        if ($current === null) {
            return [];
        }

        $nodes = Titik::query()
            ->whereIn('id', $path)
            ->get()
            ->keyBy('id');

        $segments = [];
        $accumulated = 0.0;

        foreach ($path as $index => $nodeId) {
            $node = $nodes->get($nodeId);

            if ($node === null) {
                continue;
            }

            $segments[] = [
                'id' => (int) $node->id,
                'nama' => $node->nama,
                'latitude' => (float) $node->latitude,
                'longitude' => (float) $node->longitude,
                'distance' => round($dist[$nodeId], 2),
            ];

            if ($index > 1) {
                $accumulated = $dist[$nodeId];
            }
        }

        return $segments;
    }
}