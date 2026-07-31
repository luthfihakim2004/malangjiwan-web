<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Umkm extends Model
{
    protected $fillable = [
        'nama',
        'slug',
        'owner',
        'deskripsi',
        'alamat',
        'latitude',
        'longitude',
        'jam_buka',
        'jam_tutup',
        'featured',
        'publish',
    ];
    protected $casts = [
        'featured'   => 'boolean',
        'publish'   => 'boolean',
        'latitude'  => 'decimal:7',
        'longitude' => 'decimal:7',
        'jam_buka'  => 'datetime:H:i',
        'jam_tutup' => 'datetime:H:i',
    ];

    protected function coverImage(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->media()->orderBy('sort_order')->value('path'),
        );
    }

    public function scopePublished($query)
    {
        return $query->where('publish', true);
    }

    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    public function contacts(): MorphMany
    {
        return $this->morphMany(Contact::class, 'contactable');
    }

    public function media(): MorphMany
    {
        return $this->morphMany(Media::class, 'mediable')->orderBy('sort_order');
    }

    public function posts(): MorphMany
    {
        return $this->morphMany(Post::class, 'postable')
                    ->where('publish', true)
                    ->latest('published_at');
    }
}
