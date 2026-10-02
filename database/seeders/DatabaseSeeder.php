<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            WilayahSeeder::class,
            KategoriFaskesSeeder::class,
            FaskesSeeder::class,
            GraphSeeder::class,
            // TestingSeeder::class, // DATA CONTOH (DUMMY) - aktifkan hanya untuk pengujian: php artisan db:seed --class=TestingSeeder
        ]);
    }
}