<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Models\Wisata;
use Illuminate\Http\Request;

class WisataController extends Controller
{
    public function index(Request $request)
    {
        $tagSlug = $request->query('tag');

        $wisatas = Wisata::published()
            ->when($tagSlug, fn ($q) =>
                $q->whereHas('tags', fn ($t) => $t->where('slug', $tagSlug))
            )
            ->with('tags')
            ->latest()
            ->paginate(9)
            ->withQueryString();

        $tags = Tag::whereHas('wisatas', fn ($q) => $q->where('publish', true))
            ->orderBy('nama')
            ->get();

        return view('wisata.index', [
            'wisatas' => $wisatas,
            'tags'    => $tags,
            'activeTag' => $tagSlug,
        ]);
    }

    public function show(Wisata $wisata)
    {
        abort_unless($wisata->publish, 404);

        $wisata->load([
            'tags',
            'contacts',
            'media',
            'posts' => fn ($q) => $q->with(['tags', 'media'])->latest('published_at'),
        ]);

        return view('wisata.show', compact('wisata'));
    }
}
