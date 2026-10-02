<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Faskes extends Model
{
    use HasFactory;

    protected $fillable = [
        'kategori_faskes_id',
        'wilayah_id',
        'node_id',
        'nama',
        'alamat',
        'telepon',
        'jam_operasional',
        'deskripsi',
        'latitude',
        'longitude',
    ];

    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
    ];

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(KategoriFaskes::class, 'kategori_faskes_id');
    }

    public function wilayah(): BelongsTo
    {
        return $this->belongsTo(Wilayah::class, 'wilayah_id');
    }

    public function node(): BelongsTo
    {
        return $this->belongsTo(Titik::class, 'node_id');
    }

    public function titik(): HasOne
    {
        return $this->hasOne(Titik::class, 'faskes_id');
    }
}