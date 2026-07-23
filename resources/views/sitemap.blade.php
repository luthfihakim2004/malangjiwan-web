{!! '<' . '?xml version="1.0" encoding="UTF-8"?' . '>' !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
@foreach ($staticPages as $page)
    <url>
        <loc>{{ $page['url'] }}</loc>
        @if ($page['lastmod'])
            <lastmod>{{ $page['lastmod']->toAtomString() }}</lastmod>
        @endif
    </url>
@endforeach

@foreach ($wisatas as $wisata)
    <url>
        <loc>{{ route('wisata.show', $wisata->slug) }}</loc>
        <lastmod>{{ $wisata->updated_at->toAtomString() }}</lastmod>
    </url>
@endforeach

@foreach ($umkms as $umkm)
    <url>
        <loc>{{ route('umkm.show', $umkm->slug) }}</loc>
        <lastmod>{{ $umkm->updated_at->toAtomString() }}</lastmod>
    </url>
@endforeach

@foreach ($posts as $post)
    <url>
        <loc>{{ route('post.show', $post->slug) }}</loc>
        <lastmod>{{ $post->updated_at->toAtomString() }}</lastmod>
    </url>
@endforeach
</urlset>
