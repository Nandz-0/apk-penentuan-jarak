<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Jalur extends Model
{
    use HasFactory;

    protected $table = 'edges';

    protected $fillable = [
        'node_awal',
        'node_tujuan',
        'jarak',
        'nama_jalan',
    ];

    public function nodeAwal(): BelongsTo
    {
        return $this->belongsTo(Titik::class, 'node_awal');
    }

    public function nodeTujuan(): BelongsTo
    {
        return $this->belongsTo(Titik::class, 'node_tujuan');
    }
}