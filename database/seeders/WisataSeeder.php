<?php

namespace Database\Seeders;

use App\Models\Wisata;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WisataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $wisatas = [
            [
                'nama'              => 'Umbul Brintik',
                'slug'              => 'umbul-brintik',
                'deskripsi'         => 'Initial data',
                'alamat'            => 'Jl. Umbul Brintik, Sentul, Malangjiwan, Kec. Kebonarum, Kabupaten Klaten, Jawa Tengah 57486',
                'latitude'          => '-7.6886305',
                'longitude'         => '110.5612649',
                'main_route_lat'    => '-7.687814063093515',
                'main_route_long'   => '110.5580482818675',
                'alt_route_lat'     => '-7.6868234863105265',
                'alt_route_long'    => '110.56299846664581',
                'jam_buka'          => '04:30',
                'jam_tutup'         => '16:00',
                'featured'          => true,
                'publish'           => true,

                'contacts'  => [
                    [
                        'type'  => 'phone',
                        'value' => '62895616311100'
                    ],
                    [
                        'type'  => 'instagram',
                        'value' => 'umbulbrintik'
                    ],
                    [
                        'type'  => 'whatsapp',
                        'value' => '62895616311100',
                    ],
                    [
                        'type'  => 'email',
                        'value' => 'umbulbrintik01@gmail.com'
                    ],
                ]
            ],
            [
                'nama'      => 'Umbul Bethek',
                'slug'      => 'umbul-bethek',
                'deskripsi' => 'Initial data',
                'alamat'    => 'Jl. Jagalan, Bayanan, Malangjiwan, Kec. Kebonarum, Kabupaten Klaten, Jawa Tengah 57486',
                'latitude'  => '-7.685205416682105',
                'longitude' => '110.56414062560758',
                'jam_buka'  => '05:30',
                'jam_tutup' => '17:00',
                'featured'  => true,
                'publish'   => true,
            ]
        ];

        foreach ($wisatas as $data){
            $contacts = $data['contacts'] ?? [];
            unset($data['contacts']);

            $wisata = Wisata::updateOrCreate(
                ['slug' => $data['slug']],
                $data,
            );

            $wisata->contacts()->delete();
            $wisata->contacts()->createMany($contacts);
        }
    }
}
