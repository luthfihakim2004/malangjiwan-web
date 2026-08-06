<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MapSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $path = database_path('seeders/data/Data Pemetaan - Sheet1.csv');

        if (! file_exists($path)) {
            throw new \Exception("CSV file not found: {$path}");
        }

        $handle = fopen($path, 'r');

        // Skip header
        fgetcsv($handle);

        while (($row = fgetcsv($handle)) !== false) {

            if (count($row) < 7) {
                dump('Malformed row:', $row);
                continue;
            }

            $coords = explode(',', trim($row[2]));

            if (count($coords) !== 2) {
                dump('Invalid coordinate:', $row);
                continue;
            }

            $latitude = trim($coords[0]);
            $longitude = trim($coords[1]);

            DB::table('places')->insert([
                'nama'      => $row[1],
                'kategori'  => $row[6],
                'latitude'  => $latitude,
                'longitude' => $longitude,
            ]);
        }

        fclose($handle);
    }
}
