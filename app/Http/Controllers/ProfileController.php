<?php

namespace App\Http\Controllers;

use App\Models\Profile as ProfileModel;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index()
    {
        $profil = ProfileModel::current()->load('contacts');

        $geojson = null;
        $geojsonPath = public_path('maps/malangjiwan.geojson');
        if (file_exists($geojsonPath)) {
            $decoded = json_decode(file_get_contents($geojsonPath), true);
            $geojson = $decoded['features'][0]['properties'] ?? null;
        }

        return view('profil.index', [
            'profil'    => $profil,
            'geojson'   => $geojson,
        ]);
    }
}
