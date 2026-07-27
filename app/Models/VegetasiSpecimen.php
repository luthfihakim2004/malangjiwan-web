<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VegetasiSpecimen extends Model
{
    protected $fillable = [
        'species_id',
        'kode',
        'wisata_id',
        'latitude',
        'longitude',
        'image',
        'catatan',
        'publish',
    ];

    protected $casts = [
        'publish'   => 'boolean',
        'latitude'  => 'decimal:7',
        'longitude' => 'decimal:7',
    ];

    public function species(): BelongsTo
    {
        return $this->belongsTo(VegetasiSpecies::class, 'species_id');
    }

    public function wisata(): BelongsTo
    {
        return $this->belongsTo(Wisata::class);
    }
}
