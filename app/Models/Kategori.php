<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    protected $fillable = [
        'nama',
        'slug',
        'tipe',
    ];

    public function wisatas()
    {
        return $this->morphedByMany(Wisata::class, 'kategoriable');
    }

    public function umkms()
    {
        return $this->morphedByMany(Umkm::class, 'kategoriable');
    }

    public function posts()
    {
        return $this->morphedByMany(Post::class, 'kategoriable');
    }
}
