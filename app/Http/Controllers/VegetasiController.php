<?php

namespace App\Http\Controllers;

use App\Models\VegetasiSpecies;

class VegetasiController extends Controller
{
    public function index()
    {
        $species = VegetasiSpecies::where('publish', true)
            ->with('wisata')
            ->orderBy('nama_lokal')
            ->paginate(18);

        return view('vegetasi.index', compact('species'));
    }

    public function show(VegetasiSpecies $vegetationSpecies)
    {
        abort_unless($vegetationSpecies->publish, 404);

        $vegetationSpecies->load('wisata');

        return view('vegetasi.show', ['species' => $vegetationSpecies]);
    }
}
