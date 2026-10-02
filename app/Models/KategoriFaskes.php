<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KategoriFaskes extends Model
{
    use HasFactory;

    protected $table = 'kategori_faskes';

    protected $fillable = [
        'nama',
        'deskripsi',
        'warna',
    ];

    public function faskes(): HasMany
    {
        return $this->hasMany(Faskes::class, 'kategori_faskes_id');
    }
}