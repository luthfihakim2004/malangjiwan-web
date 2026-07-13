<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Profile extends Model
{
    protected $table = 'profiles';

    protected $fillable = [
        'nama_desa',
        'sejarah',
        'visi',
        'misi',
        'struktur_organisasi',
        'alamat_kantor',
        'logo',
        'foto_kantor',
        'hero_image',
        'latitude',
        'longitude',
    ];

    /**
     * This table only ever holds one row. Always fetch (or lazily create)
     * that single record instead of querying a list.
     */
    public static function current(): self
    {
        return static::firstOrCreate(
            ['id' => 1],
            ['nama_desa' => 'Desa Malangjiwan']
        );
    }

    public function contacts(): MorphMany
    {
        return $this->morphMany(Contact::class, 'contactable');
    }
}
