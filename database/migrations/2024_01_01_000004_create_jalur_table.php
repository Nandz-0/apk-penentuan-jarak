<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jalur', function (Blueprint $table) {
            $table->id();
            $table->foreignId('titik_asal_id')->constrained('titik')->cascadeOnDelete();
            $table->foreignId('titik_tujuan_id')->constrained('titik')->cascadeOnDelete();
            $table->integer('jarak_meter')->unsigned();
            $table->string('nama_jalan', 150)->nullable();
            $table->timestamps();

            $table->index(['titik_asal_id', 'titik_tujuan_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jalur');
    }
};