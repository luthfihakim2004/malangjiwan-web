<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wisata extends Model
{
    protected $fillable = [
        'nama',
        'slug',
        'deskripsi',
        'kategori',
        'alamat',
        'latitude',
        'longitude',
        'jam_operasional',
        'kontak',
        'image',
        'featured',
        'publish',
    ];
    protected $casts = [
        'featured'   => 'boolean',
        'publish'   => 'boolean',
        'latitude'  => 'decimal:7',
        'longitude' => 'decimal:7',
    ];
}
