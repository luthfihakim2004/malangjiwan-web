<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $fillable = ['nama', 'slug'];

    public function wisatas()
    {
        return $this->morphedByMany(Wisata::class, 'taggable');
    }

    public function umkms()
    {
        return $this->morphedByMany(Umkm::class, 'taggable');
    }

    public function posts()
    {
        return $this->morphedByMany(Post::class, 'taggable');
    }
}
