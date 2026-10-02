<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('faskes', function (Blueprint $table) {
            $table->foreignId('wilayah_id')->nullable()->after('kategori_faskes_id')->constrained('wilayah')->nullOnDelete();
        });

        Schema::table('titik', function (Blueprint $table) {
            $table->foreignId('wilayah_id')->nullable()->after('faskes_id')->constrained('wilayah')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('titik', function (Blueprint $table) {
            $table->dropConstrainedForeignId('wilayah_id');
        });

        Schema::table('faskes', function (Blueprint $table) {
            $table->dropConstrainedForeignId('wilayah_id');
        });
    }
};