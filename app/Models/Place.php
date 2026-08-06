<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Place extends Model
{
    protected $fillable = [
        'nama',
        'kategori',
        'latitude',
        'longitude',
        'publish',
    ];

    protected $casts = [
        'publish'   => 'boolean',
        'latitude'  => 'decimal:7',
        'longitude' => 'decimal:7',
    ];

    public static function kategoriOptions(): array
    {
        return [
            'usaha_lokal'          => 'Usaha Lokal',
            'kuliner'       => 'Kuliner',
            'penginapan'    => 'Penginapan',
            'fasilitas_umum' => 'Fasilitas Umum',
        ];
    }

    public function scopePublished($query)
    {
        return $query->where('publish', true);
    }

    public function kategoriLabel(): string
    {
        return static::kategoriOptions()[$this->kategori] ?? $this->kategori;
    }

    /**
     * Google Maps directions URL — no waypoint, direct destination.
     */
    public function getGmapsUrlAttribute(): string
    {
        return 'https://www.google.com/maps/dir/?api=1'
            . '&destination=' . $this->latitude . ',' . $this->longitude
            . '&travelmode=driving';
    }
}
