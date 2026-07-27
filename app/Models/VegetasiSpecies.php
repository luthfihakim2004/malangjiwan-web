<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VegetasiSpecies extends Model
{
    protected $fillable = [
        'nama_lokal',
        'slug',
        'nama_ilmiah',
        'deskripsi',
        'fun_fact',
        'image',
        'publish',
    ];

    protected $casts = [
        'publish' => 'boolean',
    ];

    public function wisata(): BelongsTo
    {
        return $this->belongsTo(Wisata::class);
    }
}
