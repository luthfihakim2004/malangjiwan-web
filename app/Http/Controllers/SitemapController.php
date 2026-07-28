<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Umkm;
use App\Models\VegetasiSpecies;
use App\Models\Wisata;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;

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
                'lastmod' => ($max = Wisata::published()->max('updated_at'))
                    ? Carbon::parse($max)
                    : null,
            ],
            [
                'url' => route('umkm.index'),
                'lastmod' => ($max = Umkm::published()->max('updated_at'))
                    ? Carbon::parse($max)
                    : null,
            ],
            [
                'url' => route('post.index'),
                'lastmod' => ($max = Post::published()->max('updated_at'))
                    ? Carbon::parse($max)
                    : null,
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
            [
                'url'     => route('vegetasi.index'),
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

        $vegetasi = VegetasiSpecies::where('publish', true)
            ->select(['slug', 'updated_at'])
            ->get();

        return response()
            ->view(
                'sitemap',
                compact('staticPages', 'wisatas', 'umkms', 'posts', 'vegetasi')
            )
            ->header('Content-Type', 'application/xml');
    }
}
