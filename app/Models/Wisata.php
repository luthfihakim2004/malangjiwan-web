<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Wisata extends Model
{
    protected $fillable = [
        'nama',
        'slug',
        'deskripsi',
        'alamat',
        'latitude',
        'longitude',
        'main_route_lat',
        'main_route_long',
        'alt_route_lat',
        'alt_route_long',
        'jam_operasional',
        'featured',
        'publish',
    ];
    protected $casts = [
        'featured'   => 'boolean',
        'publish'   => 'boolean',
        'latitude'  => 'decimal:7',
        'longitude' => 'decimal:7',
        'main_route_lat'  => 'decimal:7',
        'main_route_long' => 'decimal:7',
        'alt_route_lat'   => 'decimal:7',
        'alt_route_long'  => 'decimal:7',
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

    public function getMainRouteUrlAttribute(): ?string
    {
        if ($this->latitude === null || $this->longitude === null) {
            return null;
        }

        $parameters = [
            'api' => 1,
            'destination' => "{$this->latitude},{$this->longitude}",
            'travelmode' => 'driving',
        ];

        if (
            $this->main_route_lat !== null &&
            $this->main_route_long !== null
        ) {
            $parameters['waypoints'] =
                "{$this->main_route_lat},{$this->main_route_long}";
        }

        return 'https://www.google.com/maps/dir/?'
            . http_build_query($parameters);
    }

    public function getAltRouteUrlAttribute(): ?string
    {
        if ($this->latitude === null || $this->longitude === null) {
            return null;
        }

        $parameters = [
            'api' => 1,
            'destination' => "{$this->latitude},{$this->longitude}",
            'travelmode' => 'driving',
        ];

        if (
            $this->alt_route_lat !== null &&
            $this->alt_route_long !== null
        ) {
            $parameters['waypoints'] =
                "{$this->alt_route_lat},{$this->alt_route_long}";
        }

        return 'https://www.google.com/maps/dir/?'
            . http_build_query($parameters);
    }
}
