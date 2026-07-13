<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $tagSlug = $request->query('tag');

        $posts = Post::published()
            ->when($tagSlug, fn ($q) =>
                $q->whereHas('tags', fn ($t) => $t->where('slug', $tagSlug))
            )
            ->with('tags')
            ->latest('published_at')
            ->paginate(9)
            ->withQueryString();

        $tags = Tag::whereHas('posts', fn ($q) => $q->published())
            ->orderBy('nama')
            ->get();

        return view('post.index', [
            'posts'     => $posts,
            'tags'      => $tags,
            'activeTag' => $tagSlug,
        ]);
    }

    public function show(Post $post)
    {
        abort_unless(
            $post->publish &&
            $post->published_at &&
            $post->published_at->lte(now()),
            404
        );

        $post->load(['tags', 'media']);

        return view('post.show', compact('post'));
    }
}
