<?php

namespace App\Services;

use App\Models\Jalur;
use App\Models\Titik;

/**
 * Service Algoritma Dijkstra.
 *
 * Kode sengaja ditulis sederhana dan berkomentar agar mudah dibaca dan
 * dipahami mahasiswa. Tidak ada struktur data rumit (mis. priority queue);
 * pemilihan node dengan jarak terkecil dilakukan dengan penelusuran biasa,
 * karena jumlah node pada penelitian ini relatif sedikit.
 */
class DijkstraService
{
    /**
     * Grafik jalan dalam bentuk adjacency list.
     * Format: [id_node => [id_tetangga => jarak_meter]]
     *
     * @var array<int, array<int, int>>
     */
    private array $graph = [];

    public function __construct()
    {
        $this->buildGraph();
    }

    /**
     * Membangun adjacency list dari seluruh data jalur (edge) pada database.
     * Edge diperlakukan dua arah, sehingga graf dapat ditelusuri dari
     * node_awal ke node_tujuan maupun sebaliknya.
     */
    public function buildGraph(): void
    {
        $this->graph = [];

        Jalur::query()->each(function (Jalur $jalur) {
            $this->graph[$jalur->node_awal][$jalur->node_tujuan] = $jalur->jarak;
            $this->graph[$jalur->node_tujuan][$jalur->node_awal] = $jalur->jarak;
        });
    }

    /**
     * Inti Algoritma Dijkstra pada sebuah adjacency list.
     *
     * Langkah-langkahnya sengaja dipisah dan diberi komentar agar mudah
     * diikuti pembaca.
     *
     * @param  array<int, array<int, int>>  $graph  adjacency list [node => [tetangga => jarak]]
     * @return array{dist: array<int,float>, prev: array<int,int|null>}
     */
    private function dijkstraCore(array $graph, int $sourceId): array
    {
        // Mengumpulkan seluruh node yang muncul pada graf (node asal maupun tujuan).
        $nodes = [];
        foreach ($graph as $from => $neighbors) {
            $nodes[$from] = true;
            foreach ($neighbors as $to => $weight) {
                $nodes[$to] = true;
            }
        }

        // Inisialisasi jarak: jarak node awal = 0, node lainnya = tak hingga (INF).
        $dist = [];
        $prev = [];
        foreach (array_keys($nodes) as $node) {
            $dist[$node] = INF;
            $prev[$node] = null;
        }

        if (! array_key_exists($sourceId, $dist)) {
            return ['dist' => $dist, 'prev' => $prev];
        }

        $dist[$sourceId] = 0;

        // Daftar node yang belum dikunjungi.
        $unvisited = array_keys($nodes);

        // Ulangi sampai semua node selesai dikunjungi (atau sisanya tak terjangkau).
        while (count($unvisited) > 0) {
            // Menentukan node dengan jarak terkecil di antara node yang belum dikunjungi.
            $currentId = null;
            foreach ($unvisited as $node) {
                if ($currentId === null || $dist[$node] < $dist[$currentId]) {
                    $currentId = $node;
                }
            }

            // Jika node terdekat masih tak hingga, node lain tidak dapat dijangkau.
            if ($dist[$currentId] === INF) {
                break;
            }

            // Tandai node ini sudah dikunjungi dengan mengeluarkannya dari daftar.
            $unvisited = array_values(array_diff($unvisited, [$currentId]));

            // Memeriksa tetangga dari node yang sedang diproses.
            $neighbors = $graph[$currentId] ?? [];
            foreach ($neighbors as $neighborId => $weight) {
                $newDist = $dist[$currentId] + $weight;

                // Update jarak bila ditemukan jalur yang lebih pendek.
                if ($newDist < $dist[$neighborId]) {
                    $dist[$neighborId] = $newDist;

                    // Menyimpan previous node agar jalur dapat ditelusuri kembali.
                    $prev[$neighborId] = $currentId;
                }
            }
        }

        return ['dist' => $dist, 'prev' => $prev];
    }

    /**
     * Mencari lintasan terpendek (Dijkstra) antara node awal dan node tujuan.
     *
     * @param  array<int, array<int, int>>  $graph  adjacency list [node => [tetangga => jarak]]
     * @return array{distance: float, path: array<int, int>}|null
     */
    public function findShortestPath(array $graph, int $startNode, int $targetNode): ?array
    {
        // Bila node awal sama dengan node tujuan, jaraknya 0.
        if ($startNode === $targetNode) {
            return ['distance' => 0.0, 'path' => [$startNode]];
        }

        $result = $this->dijkstraCore($graph, $startNode);

        // Jika jarak tujuan masih tak hingga, berarti tujuan tidak terjangkau.
        if (! isset($result['dist'][$targetNode]) || ! is_finite($result['dist'][$targetNode])) {
            return null;
        }

        // Membentuk kembali jalur terpendek dengan menelusuri previous node
        // mulai dari node tujuan hingga kembali ke node awal.
        $path = [];
        $current = $targetNode;
        while ($current !== null && $current !== $startNode) {
            array_unshift($path, $current);
            $current = $result['prev'][$current] ?? null;
        }

        // Jalur tidak valid bila penelusuran tidak sampai ke node awal.
        if ($current === null) {
            return null;
        }

        array_unshift($path, $startNode);

        return [
            'distance' => (float) $result['dist'][$targetNode],
            'path' => $path,
        ];
    }

    /**
     * Menjalankan Dijkstra dari satu node awal pada graf yang sudah dibangun.
     * Mengembalikan jarak (dist) dan previous node (prev) dari seluruh node.
     *
     * @return array{dist: array<int,float>, prev: array<int,int|null>}
     */
    public function run(int $sourceId): array
    {
        return $this->dijkstraCore($this->graph, $sourceId);
    }

    /**
     * Mencari jarak dan lintasan terpendek antara dua node pada graf ter-build.
     *
     * @return array{distance: float, path: array<int, int>}|null
     */
    public function shortestPath(int $sourceId, int $goalId): ?array
    {
        return $this->findShortestPath($this->graph, $sourceId, $goalId);
    }

    /**
     * Mencari node terdekat dari koordinat pengguna memakai rumus Haversine.
     * Alur: koordinat pengguna -> bandingkan ke seluruh node -> ambil yang
     * jaraknya paling kecil sebagai node awal.
     *
     * @param  string|null  $tipe  filter opsional (mis. 'jalan' untuk node jalan)
     */
    public function findNearestNode(float $latitude, float $longitude, ?string $tipe = null): ?Titik
    {
        $query = Titik::query();
        if ($tipe !== null) {
            $query->where('tipe', $tipe);
        }

        $nearest = null;
        $nearestDist = INF;

        $query->get()->each(function (Titik $titik) use ($latitude, $longitude, &$nearest, &$nearestDist) {
            $dist = $this->haversine($latitude, $longitude, (float) $titik->latitude, (float) $titik->longitude);
            if ($dist < $nearestDist) {
                $nearestDist = $dist;
                $nearest = $titik;
            }
        });

        return $nearest;
    }

    /**
     * Jarak garis lurus (Haversine) antara dua koordinat, dalam meter.
     */
    public function haversine(float $lat1, float $lng1, float $lat2, float $lng2): float
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
