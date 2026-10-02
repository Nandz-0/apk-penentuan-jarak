<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::disableForeignKeyConstraints();

        Schema::rename('jalur', 'edges');

        Schema::table('edges', function ($table) {
            $table->dropForeign('jalur_titik_asal_id_foreign');
            $table->dropForeign('jalur_titik_tujuan_id_foreign');
            $table->dropIndex('jalur_titik_asal_id_titik_tujuan_id_index');
        });

        DB::statement("ALTER TABLE `edges`
            CHANGE `titik_asal_id` `node_awal` BIGINT(20) UNSIGNED NOT NULL,
            CHANGE `titik_tujuan_id` `node_tujuan` BIGINT(20) UNSIGNED NOT NULL,
            CHANGE `jarak_meter` `jarak` INT(10) UNSIGNED NOT NULL");

        Schema::table('edges', function ($table) {
            $table->foreign('node_awal')->references('id')->on('nodes')->onDelete('cascade');
            $table->foreign('node_tujuan')->references('id')->on('nodes')->onDelete('cascade');
            $table->index(['node_awal', 'node_tujuan']);
        });

        Schema::enableForeignKeyConstraints();
    }

    public function down(): void
    {
        Schema::disableForeignKeyConstraints();

        Schema::table('edges', function ($table) {
            $table->dropForeign(['node_awal']);
            $table->dropForeign(['node_tujuan']);
            $table->dropIndex('edges_node_awal_node_tujuan_index');
        });

        DB::statement("ALTER TABLE `edges`
            CHANGE `node_awal` `titik_asal_id` BIGINT(20) UNSIGNED NOT NULL,
            CHANGE `node_tujuan` `titik_tujuan_id` BIGINT(20) UNSIGNED NOT NULL,
            CHANGE `jarak` `jarak_meter` INT(10) UNSIGNED NOT NULL");

        Schema::table('edges', function ($table) {
            $table->foreign('titik_asal_id')->references('id')->on('nodes')->onDelete('cascade');
            $table->foreign('titik_tujuan_id')->references('id')->on('nodes')->onDelete('cascade');
            $table->index(['titik_asal_id', 'titik_tujuan_id']);
        });

        Schema::rename('edges', 'jalur');

        Schema::enableForeignKeyConstraints();
    }
};