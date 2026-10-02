<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Titik extends Model
{
    use HasFactory;

    protected $table = 'nodes';

    protected $fillable = [
        'nama',
        'kode',
        'latitude',
        'longitude',
        'tipe',
        'faskes_id',
        'wilayah_id',
    ];

    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
    ];

    public function faskes(): HasMany
    {
        return $this->hasMany(Faskes::class, 'node_id');
    }

    public function faskesInduk(): BelongsTo
    {
        return $this->belongsTo(Faskes::class, 'faskes_id');
    }

    public function wilayah(): BelongsTo
    {
        return $this->belongsTo(Wilayah::class, 'wilayah_id');
    }

    public function edgesAwal(): HasMany
    {
        return $this->hasMany(Jalur::class, 'node_awal');
    }

    public function edgesTujuan(): HasMany
    {
        return $this->hasMany(Jalur::class, 'node_tujuan');
    }
}