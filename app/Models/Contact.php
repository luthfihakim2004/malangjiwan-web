<?php

namespace App\Models;

use App\Enums\ContactType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Contact extends Model
{
    protected $fillable = [
        'type',
        'label',
        'value',
    ];

    protected $casts = [
        'type' => ContactType::class,
    ];

    /**
     * Get the parent contactable model (polymorphic).
     */
    public function contactable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the formatted URL for this contact.
     */
    public function getUrlAttribute(): ?string
    {
        if (empty($this->value)) {
            return null;
        }

        return match ($this->type) {
            ContactType::WhatsApp => 'https://wa.me/' . $this->value,
            ContactType::Phone => 'tel:+' . $this->value,
            ContactType::Instagram => 'https://instagram.com/' . $this->value,
            ContactType::Facebook => $this->formatSocialUrl($this->value, 'facebook.com'),
            ContactType::TikTok => 'https://www.tiktok.com/@' . $this->value,
            ContactType::YouTube => $this->formatSocialUrl($this->value, 'youtube.com'),
            ContactType::Email => 'mailto:' . $this->value,
            ContactType::Website => $this->formatWebsiteUrl($this->value),
            default => null,
        };
    }

    /**
     * Format social media URLs (Facebook, YouTube, etc.).
     */
    protected function formatSocialUrl(string $value, string $platform): string
    {
        return str_starts_with($value, 'http')
            ? $value
            : "https://{$platform}/" . ltrim($value, '/');
    }

    /**
     * Format website URL.
     */
    protected function formatWebsiteUrl(string $value): string
    {
        if (str_starts_with($value, 'http')) {
            return $value;
        }

        return 'https://' . ltrim($value, '/');
    }

    protected static function booted(): void
    {
        static::saving(function (Contact $contact) {
            // Normalize phone numbers (WhatsApp & Phone)
            if (in_array($contact->type, [ContactType::Phone, ContactType::WhatsApp])) {
                $contact->value = self::normalizePhone($contact->value);
            }

            // Remove leading @ for Instagram and TikTok
            if (in_array($contact->type, [ContactType::Instagram, ContactType::TikTok])) {
                $contact->value = ltrim($contact->value, '@');
            }
        });
    }

    public static function normalizePhone(?string $number): ?string
    {
        if (!$number) {
            return null;
        }

        // Remove all non-digit characters
        $number = preg_replace('/[^0-9]/', '', $number);

        // Indonesian number normalization (common patterns)
        if (str_starts_with($number, '08')) {
            return '62' . substr($number, 1);
        }

        if (str_starts_with($number, '8')) {
            return '62' . $number;
        }

        // Keep international numbers as-is (already starting with country code)
        return $number;
    }
}
