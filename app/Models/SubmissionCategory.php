<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SubmissionCategory extends Model
{
    protected $fillable = ['nama', 'slug', 'active'];

    protected $casts = ['active' => 'boolean'];

    public function submissions(): HasMany
    {
        return $this->hasMany(Submission::class, 'category_id');
    }
}
