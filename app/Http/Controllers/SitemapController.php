<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Umkm;
use App\Models\Wisata;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function __invoke(): Response
    {
        $staticPages = [
            [
                'url' => route('home'),
                'lastmod' => null,
            ],
            [
                'url' => route('wisata.index'),
                'lastmod' => Wisata::published()->max('updated_at'),
            ],
            [
                'url' => route('umkm.index'),
                'lastmod' => Umkm::published()->max('updated_at'),
            ],
            [
                'url' => route('post.index'),
                'lastmod' => Post::published()->max('updated_at'),
            ],
            [
                'url' => route('galeri.index'),
                'lastmod' => null,
            ],
            [
                'url' => route('peta'),
                'lastmod' => null,
            ],
            [
                'url' => route('profil'),
                'lastmod' => null,
            ],
        ];

        $wisatas = Wisata::published()
            ->select(['slug', 'updated_at'])
            ->get();

        $umkms = Umkm::published()
            ->select(['slug', 'updated_at'])
            ->get();

        $posts = Post::published()
            ->select(['slug', 'updated_at'])
            ->get();

        return response()
            ->view(
                'sitemap',
                compact('staticPages', 'wisatas', 'umkms', 'posts')
            )
            ->header('Content-Type', 'application/xml');
    }
}
