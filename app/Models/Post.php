<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'judul',
        'slug',
        'excerpt',
        'body',
        'image',
        'kategori',
        'publish'
    ];
    protected $casts = [
        'publish'       => 'boolean',
        'published_at'  => 'datetime',
    ];
}
