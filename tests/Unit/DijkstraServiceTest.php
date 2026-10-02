<?php

namespace Tests\Unit;

use App\Services\DijkstraService;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

/**
 * Unit Test Algoritma Dijkstra.
 *
 * Contoh graf yang diuji (dua arah / undirected, sama seperti
 * perlakuan pada method buildGraph()):
 *
 *      A --4-- B
 *      |     / |
 *      2    1  5
 *      |  /    |
 *      C --8-- D
 *
 * Keterangan edge:
 *   A -> B = 4
 *   A -> C = 2
 *   B -> C = 1
 *   B -> D = 5
 *   C -> D = 8
 *
 * Node direpresentasikan dengan angka karena service memakai id integer:
 *   A = 1, B = 2, C = 3, D = 4.
 */
class DijkstraServiceTest extends TestCase
{
    /**
     * Membuat instance DijkstraService TANPA memanggil constructor.
     * Tujuannya agar unit test tidak bergantung pada koneksi database
     * (method findShortestPath menerima graph secara langsung).
     */
    private function service(): DijkstraService
    {
        return (new ReflectionClass(DijkstraService::class))->newInstanceWithoutConstructor();
    }

    /**
     * Menyusun graf contoh di atas sebagai adjacency list dua arah.
     *
     * @return array<int, array<int, int>>
     */
    private function exampleGraph(): array
    {
        $edges = [
            [1, 2, 4], // A -> B = 4
            [1, 3, 2], // A -> C = 2
            [2, 3, 1], // B -> C = 1
            [2, 4, 5], // B -> D = 5
            [3, 4, 8], // C -> D = 8
        ];

        $graph = [];
        foreach ($edges as [$from, $to, $weight]) {
            $graph[$from][$to] = $weight;
            $graph[$to][$from] = $weight;
        }

        return $graph;
    }

    /**
     * Jalur normal: A -> D.
     * Jalur terpendek yang benar adalah A -> C -> B -> D
     * dengan jarak 2 + 1 + 5 = 8.
     */
    public function test_jalur_normal_menghasilkan_path_dan_distance_yang_benar(): void
    {
        $result = $this->service()->findShortestPath($this->exampleGraph(), 1, 4);

        $this->assertNotNull($result);
        $this->assertSame(8.0, $result['distance']);
        $this->assertSame([1, 3, 2, 4], $result['path']);
    }

    /**
     * Memastikan jalur terpendek tidak sekadar memilih jalur langsung,
     * tetapi benar-benar membandingkan seluruh kemungkinan.
     */
    public function test_memilih_jalur_terpendek_bukan_jalur_langsung(): void
    {
        // A -> C seharusnya 2 (bukan lewat B: 4 + 1 = 5).
        $result = $this->service()->findShortestPath($this->exampleGraph(), 1, 3);

        $this->assertNotNull($result);
        $this->assertSame(2.0, $result['distance']);
        $this->assertSame([1, 3], $result['path']);
    }

    /**
     * Node tujuan tidak ditemukan pada graf.
     */
    public function test_node_tujuan_tidak_ditemukan(): void
    {
        $result = $this->service()->findShortestPath($this->exampleGraph(), 1, 99);

        $this->assertNull($result);
    }

    /**
     * Node awal tidak ditemukan pada graf.
     */
    public function test_node_awal_tidak_ditemukan(): void
    {
        $result = $this->service()->findShortestPath($this->exampleGraph(), 99, 4);

        $this->assertNull($result);
    }

    /**
     * Graf kosong: tidak ada node sama sekali.
     */
    public function test_graph_kosong(): void
    {
        $result = $this->service()->findShortestPath([], 1, 4);

        $this->assertNull($result);
    }

    /**
     * Node tidak terhubung: A-B satu komponen, C-D komponen lain.
     * A -> D harus mengembalikan null karena tidak ada jalur.
     */
    public function test_node_tidak_terhubung(): void
    {
        $graph = [
            1 => [2 => 10], // A -- B = 10
            2 => [1 => 10],
            3 => [4 => 10], // C -- D = 10
            4 => [3 => 10],
        ];

        // Tidak ada jalur dari komponen A-B menuju komponen C-D.
        $this->assertNull($this->service()->findShortestPath($graph, 1, 4));

        // Namun di dalam komponen yang sama tetap ditemukan jalurnya.
        $result = $this->service()->findShortestPath($graph, 1, 2);
        $this->assertNotNull($result);
        $this->assertSame(10.0, $result['distance']);
        $this->assertSame([1, 2], $result['path']);
    }

    /**
     * Node awal sama dengan node tujuan: jarak 0, jalur berisi node itu sendiri.
     */
    public function test_node_awal_sama_dengan_tujuan(): void
    {
        $result = $this->service()->findShortestPath($this->exampleGraph(), 1, 1);

        $this->assertNotNull($result);
        $this->assertSame(0.0, $result['distance']);
        $this->assertSame([1], $result['path']);
    }
}
