<?php

namespace Database\Seeders;

use App\Models\Profile;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProfileSeeder extends Seeder
{
    public function run(): void
    {
        $profile = Profile::find(1);

        if ($profile) {
            $profile->update([
                'nama_desa'     => 'Desa Malangjiwan',
                'alamat_kantor' => '8H65+WVG, Sentul, Malangjiwan, Kec. Kebonarum, Kabupaten Klaten, Jawa Tengah 57486',
                'latitude'      => '-7.687577983087015',
                'longitude'     => '110.55973381943808',
            ]);

            $profile->contacts()->updateOrCreate(
                ['type' => 'instagram'],
                ['value' => 'desa_malangjiwan']
            );

            $profile->contacts()->updateOrCreate(
                ['type' => 'email'],
                ['value' => 'pemerintahdesamalangjiwan@gmail.com']
            );
        }
    }
}
