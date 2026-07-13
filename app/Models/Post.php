<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'judul',
        'slug',
        'excerpt',
        'body',
        'publish',
        'published_at'
    ];
    protected $casts = [
        'publish'       => 'boolean',
        'published_at'  => 'datetime',
    ];

    protected static function booted(): void
    {
        static::saving(function (Post $post) {
            // Atur tanggal publikasi saat pertama kali publish
            if ($post->publish && is_null($post->published_at)) {
                $post->published_at = now();
            }

            // Hapus tanggal publikasi jika di unpublish (takedown post)
            if (! $post->publish) {
                $post->published_at = null;
            }
        });
    }

    protected function coverImage(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->media()->value('path'),
        );
    }

    public function scopePublished($query)
    {
        return $query
            ->where('publish', true)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    public function media(): MorphMany
    {
        return $this->morphMany(Media::class, 'mediable')->orderBy('sort_order');
    }
}
