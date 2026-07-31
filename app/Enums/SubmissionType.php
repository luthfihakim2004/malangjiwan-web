<?php

namespace App\Enums;

enum SubmissionType: string
{
    case Pengaduan = 'pengaduan';
    case Laporan   = 'laporan';
    case Kritik    = 'kritik';
    case Saran     = 'saran';

    public function label(): string
    {
        return match($this) {
            self::Pengaduan => 'Pengaduan',
            self::Laporan   => 'Laporan',
            self::Kritik    => 'Kritik',
            self::Saran     => 'Saran',
        };
    }

    public function description(): string
    {
        return match($this) {
            self::Pengaduan => 'Keluhan atau masalah yang perlu ditindaklanjuti',
            self::Laporan   => 'Pelaporan kejadian atau kondisi tertentu',
            self::Kritik    => 'Evaluasi atau penilaian negatif yang membangun',
            self::Saran     => 'Ide atau masukan untuk perbaikan',
        };
    }

    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn ($case) => [$case->value => $case->label()])
            ->all();
    }
}
