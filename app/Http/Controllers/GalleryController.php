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
            'wisata' => Wisata::class,
            'umkm' => Umkm::class,
            'post' => Post::class,
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
                        $q->where('mediable_type', Wisata::class)
                            ->whereHasMorph('mediable', [Wisata::class], fn ($q) => $q->published())
                    )
                    ->orWhere(fn ($q) =>
                        $q->where('mediable_type', Umkm::class)
                            ->whereHasMorph('mediable', [Umkm::class], fn ($q) => $q->published())
                    )
                    ->orWhere(fn ($q) =>
                        $q->where('mediable_type', Post::class)
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
