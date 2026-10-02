<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('faskes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kategori_faskes_id')->constrained('kategori_faskes')->cascadeOnDelete();
            $table->string('nama', 150);
            $table->text('alamat');
            $table->string('telepon', 30)->nullable();
            $table->string('jam_operasional', 100)->nullable();
            $table->text('deskripsi')->nullable();
            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 10, 7);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('faskes');
    }
};