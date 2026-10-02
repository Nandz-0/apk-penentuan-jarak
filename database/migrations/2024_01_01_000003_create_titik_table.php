<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('titik', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 150);
            $table->string('kode', 20)->nullable();
            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 10, 7);
            $table->enum('tipe', ['faskes', 'jalan'])->default('jalan');
            $table->foreignId('faskes_id')->nullable()->constrained('faskes')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('titik');
    }
};