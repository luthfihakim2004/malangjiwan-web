<?php

namespace App\Models;

use App\Enums\SubmissionStatus;
use App\Enums\SubmissionType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Hash;

class Submission extends Model
{
    protected $fillable = [
        'tracking_code',
        'tracking_pin_hash',
        'type',
        'title',
        'description',
        'status',
        'priority',
        'category_id',
        'recipient_type',
        'recipient_id',
        'identity_mode',
        'reporter_name',
        'reporter_phone',
        'reporter_email',
        'latitude',
        'longitude',
        'location_description',
        'incident_date',
        'attachment',
        'public_note',
        'submitted_at',
        'resolved_at',
    ];

    protected $casts = [
        'status'        => SubmissionStatus::class,
        'type'          => SubmissionType::class,
        'incident_date' => 'date',
        'submitted_at'  => 'datetime',
        'resolved_at'   => 'datetime',
        'latitude'      => 'decimal:7',
        'longitude'     => 'decimal:7',
    ];

    // ── Relationships ──────────────────────────────────

    public function category(): BelongsTo
    {
        return $this->belongsTo(SubmissionCategory::class);
    }

    public function recipient(): MorphTo
    {
        return $this->morphTo();
    }

    // ── Helpers ────────────────────────────────────────

    /**
     * Generate a unique tracking code: ASP-{YEAR}-{SEQUENCE}
     */
    public static function generateTrackingCode(): string
    {
        $year = now()->year;
        $last = static::whereYear('created_at', $year)
            ->latest('id')
            ->value('tracking_code');

        $seq = $last
            ? (int) substr($last, -4) + 1
            : 1;

        return 'ASP-' . $year . '-' . str_pad($seq, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Generate a plain 4-digit PIN and return it (caller stores the hash).
     */
    public static function generatePin(): string
    {
        return str_pad(random_int(0, 9999), 4, '0', STR_PAD_LEFT);
    }

    public function verifyPin(string $pin): bool
    {
        return Hash::check($pin, $this->tracking_pin_hash);
    }

    public function recipientLabel(): string
    {
        if (! $this->recipient) {
            return 'Pemerintah Desa';
        }

        return match($this->recipient_type) {
            'wisata' => 'Wisata: ' . $this->recipient->nama,
            'umkm'   => 'UMKM: ' . $this->recipient->nama,
            default  => 'Pemerintah Desa',
        };
    }
}
