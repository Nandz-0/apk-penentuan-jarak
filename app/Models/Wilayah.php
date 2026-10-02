<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Wilayah extends Model
{
    use HasFactory;

    protected $table = 'wilayah';

    protected $fillable = [
        'nama',
        'kota',
        'provinsi',
    ];

    public function faskes(): HasMany
    {
        return $this->hasMany(Faskes::class);
    }

    public function titik(): HasMany
    {
        return $this->hasMany(Titik::class);
    }
}