<?php

namespace App\Enums;

enum ContactType: string
{
    case Phone = 'phone';
    case WhatsApp = 'whatsapp';
    case Email = 'email';
    case Website = 'website';

    case Instagram = 'instagram';
    case Facebook = 'facebook';
    case TikTok = 'tiktok';
    case YouTube = 'youtube';

    /**
     * For Filament Select::options()
     */
    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn (self $case) => [
                $case->value => match ($case) {
                    self::Phone => 'Telepon',
                    self::WhatsApp => 'WhatsApp',
                    self::Email => 'Email',
                    self::Website => 'Website',
                    self::Instagram => 'Instagram',
                    self::Facebook => 'Facebook',
                    self::TikTok => 'TikTok',
                    self::YouTube => 'YouTube',
                },
            ])
            ->all();
    }
}
