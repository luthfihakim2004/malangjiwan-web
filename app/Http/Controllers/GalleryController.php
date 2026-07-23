<?php

namespace App\Http\Controllers;

use App\Models\Media;
use App\Models\Post;
use App\Models\Umkm;
use App\Models\Wisata;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    public function index(Request $request)
    {
        $tipe = $request->query('tipe');

        $morphMap = [
            'wisata'    => 'wisata',
            'umkm'      => 'umkm',
            'post'      => 'post',
        ];

        $media = Media::with('mediable')
            ->where(function ($query) use ($tipe, $morphMap) {
                if ($tipe && isset($morphMap[$tipe])) {
                    $class = $morphMap[$tipe];

                    $query->where('mediable_type', $class)
                        ->whereHasMorph('mediable', [$class], fn ($q) => $q->published());

                    return;
                }

                $query
                    ->orWhere(fn ($q) =>
                        $q->where('mediable_type', 'wisata')
                            ->whereHasMorph('mediable', [Wisata::class], fn ($q) => $q->published())
                    )
                    ->orWhere(fn ($q) =>
                        $q->where('mediable_type', 'umkm')
                            ->whereHasMorph('mediable', [Umkm::class], fn ($q) => $q->published())
                    )
                    ->orWhere(fn ($q) =>
                        $q->where('mediable_type', 'post')
                            ->whereHasMorph('mediable', [Post::class], fn ($q) => $q->published())
                    );
            })
            ->latest()
            ->paginate(24)
            ->withQueryString();

        return view('galeri.index', [
            'media' => $media,
            'activeTipe' => $tipe,
        ]);
    }
}
