<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Models\Umkm;
use Illuminate\Http\Request;

class UmkmController extends Controller
{
    public function index(Request $request)
    {
        $tagSlug = $request->query('tag');

        $umkms = Umkm::published()
            ->when($tagSlug, fn ($q) =>
                $q->whereHas('tags', fn ($t) => $t->where('slug', $tagSlug))
            )
            ->with('tags')
            ->latest()
            ->paginate(9)
            ->withQueryString();

        $tags = Tag::whereHas('umkms', fn ($q) => $q->where('publish', true))
            ->orderBy('nama')
            ->get();

        return view('umkm.index', [
            'umkms'     => $umkms,
            'tags'      => $tags,
            'activeTag' => $tagSlug,
        ]);
    }

    public function show(Umkm $umkm)
    {
        abort_unless($umkm->publish, 404);

        $umkm->load(['tags', 'contacts', 'media']);

        return view('umkm.show', compact('umkm'));
    }
}
