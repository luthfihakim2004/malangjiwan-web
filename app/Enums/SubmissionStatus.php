<?php

namespace App\Enums;

enum SubmissionStatus: string
{
    case Diterima              = 'diterima';
    case Diverifikasi          = 'diverifikasi';
    case Diteruskan            = 'diteruskan';
    case SedangDitangani       = 'sedang_ditangani';
    case MenungguInformasi     = 'menunggu_informasi';
    case Selesai               = 'selesai';
    case Ditolak               = 'ditolak';
    case Ditutup               = 'ditutup';

    public function label(): string
    {
        return match($this) {
            self::Diterima          => 'Diterima',
            self::Diverifikasi      => 'Diverifikasi',
            self::Diteruskan        => 'Diteruskan',
            self::SedangDitangani   => 'Sedang Ditangani',
            self::MenungguInformasi => 'Menunggu Informasi Pelapor',
            self::Selesai           => 'Selesai',
            self::Ditolak           => 'Ditolak',
            self::Ditutup           => 'Ditutup',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::Diterima          => 'blue',
            self::Diverifikasi      => 'indigo',
            self::Diteruskan        => 'purple',
            self::SedangDitangani   => 'yellow',
            self::MenungguInformasi => 'orange',
            self::Selesai           => 'green',
            self::Ditolak           => 'red',
            self::Ditutup           => 'gray',
        };
    }

    // CSS classes for the public tracking page badge
    public function badgeClass(): string
    {
        return match($this) {
            self::Diterima          => 'bg-blue-100 text-blue-800',
            self::Diverifikasi      => 'bg-indigo-100 text-indigo-800',
            self::Diteruskan        => 'bg-purple-100 text-purple-800',
            self::SedangDitangani   => 'bg-yellow-100 text-yellow-800',
            self::MenungguInformasi => 'bg-orange-100 text-orange-800',
            self::Selesai           => 'bg-green-100 text-green-800',
            self::Ditolak           => 'bg-red-100 text-red-800',
            self::Ditutup           => 'bg-gray-100 text-gray-700',
        };
    }

    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn ($case) => [$case->value => $case->label()])
            ->all();
    }

    public function isTerminal(): bool
    {
        return in_array($this, [self::Selesai, self::Ditolak, self::Ditutup]);
    }
}
