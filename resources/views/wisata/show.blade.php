@extends('layouts.app')

@section('title', $wisata->nama . ' — Wisata Desa Malangjiwan, Klaten')
@section('meta_description', Str::limit("{$wisata->nama} adalah destinasi wisata di Desa Malangjiwan, Kebonarum, Klaten. " . strip_tags($wisata->deskripsi), 155, ''))
@section('canonical', route('wisata.show', $wisata->slug))
@if ($wisata->cover_image)
    @section('og_image', asset('storage/' . $wisata->cover_image))
@endif

@php
    $wisataSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'TouristAttraction',
        'name' => $wisata->nama,
        'description' => Str::limit(
            strip_tags($wisata->deskripsi),
            300,
            ''
        ),
        'url' => route('wisata.show', $wisata->slug),
        'image' => [$wisata->seo_image_url],
    ];

    if ($wisata->alamat) {
        $wisataSchema['address'] = [
            '@type' => 'PostalAddress',
            'streetAddress' => $wisata->alamat,
            'addressLocality' => 'Malangjiwan',
            'addressRegion' => 'Jawa Tengah',
            'addressCountry' => 'ID',
        ];
    }

    if ($wisata->latitude && $wisata->longitude) {
        $wisataSchema['geo'] = [
            '@type' => 'GeoCoordinates',
            'latitude' => (float) $wisata->latitude,
            'longitude' => (float) $wisata->longitude,
        ];
    }
@endphp

@push('structured-data')
<script type="application/ld+json">
{!! json_encode(
    $wisataSchema,
    JSON_UNESCAPED_SLASHES
    | JSON_UNESCAPED_UNICODE
    | JSON_PRETTY_PRINT
) !!}
</script>
@endpush

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-14">

    <nav class="mb-8 text-sm text-[var(--color-ink)]/50 flex items-center gap-2">
        <a href="{{ route('home') }}" class="hover:text-[var(--color-bata)]">Beranda</a>
        <span>/</span>
        <a href="{{ route('wisata.index') }}" class="hover:text-[var(--color-bata)]">Wisata</a>
        <span>/</span>
        <span class="text-[var(--color-ink)]/80 line-clamp-1">{{ $wisata->nama }}</span>
    </nav>

    {{-- Gallery slider --}}
    <div class="mb-10">
        <x-gallery-slider
            :media="$wisata->media"
            :fallback="$wisata->image"
            aspect="aspect-video"
            :alt="$wisata->nama"
        />
    </div>

    @php
        // Build map markers — destination pin + optional waypoint pins
        $mapMarkers = [];

        if ($wisata->latitude && $wisata->longitude) {
            $mapMarkers[] = [
                'lat'      => (float) $wisata->latitude,
                'lng'      => (float) $wisata->longitude,
                'nama'     => $wisata->nama,
                'type'     => 'wisata',
                'kategori' => $wisata->tags->pluck('nama')->join(', '),
                'url'      => null,
            ];
        }

        if ($wisata->main_route_lat && $wisata->main_route_long) {
            $mapMarkers[] = [
                'lat'      => (float) $wisata->main_route_lat,
                'lng'      => (float) $wisata->main_route_long,
                'nama'     => 'Titik Masuk Utama — ' . $wisata->nama,
                'type'     => 'route_main',
                'kategori' => null,
                'url'      => $wisata->main_route_url,
            ];
        }

        if ($wisata->alt_route_lat && $wisata->alt_route_long) {
            $mapMarkers[] = [
                'lat'      => (float) $wisata->alt_route_lat,
                'lng'      => (float) $wisata->alt_route_long,
                'nama'     => 'Titik Masuk Alternatif — ' . $wisata->nama,
                'type'     => 'route_alt',
                'kategori' => null,
                'url'      => $wisata->alt_route_url,
            ];
        }

        // Determine which route buttons to show
        $hasMainRoute = $wisata->main_route_url !== null;
        $hasAltRoute  = $wisata->alt_route_url !== null;
        $hasBothWaypoints = $wisata->main_route_lat && $wisata->alt_route_lat;
    @endphp

    <div class="grid lg:grid-cols-5 gap-10">

        {{-- Right: info --}}
        <div class="lg:col-span-3">

            @if ($wisata->tags->isNotEmpty())
                <div class="flex flex-wrap gap-2 mb-3">
                    @foreach ($wisata->tags as $tag)
                        <a href="{{ route('wisata.index', ['tag' => $tag->slug]) }}"
                           class="eyebrow px-3 py-1 rounded-full bg-[var(--color-bamboo)] hover:bg-[var(--color-bamboo-dark)] transition-colors">
                            {{ $tag->nama }}
                        </a>
                    @endforeach
                </div>
            @endif

            <h1 class="font-display text-3xl sm:text-4xl font-semibold text-[var(--color-sawah-deep)] leading-tight">
                {{ $wisata->nama }}
            </h1>

            @if ($wisata->deskripsi)
                <p class="mt-6 text-[var(--color-ink)]/80 leading-relaxed text-justify hyphens-auto" lang="id">
                    {{ $wisata->deskripsi }}
                </p>
            @endif

            <dl class="mt-8 space-y-4">
                @if ($wisata->alamat)
                    <div class="flex gap-3">
                        <dt class="shrink-0 w-36 text-sm font-medium text-[var(--color-ink)]/50">Alamat</dt>
                        <dd class="text-sm text-[var(--color-ink)]">{{ $wisata->alamat }}</dd>
                    </div>
                @endif
                @if ($wisata->jam_operasional)
                    <div class="flex gap-3">
                        <dt class="shrink-0 w-36 text-sm font-medium text-[var(--color-ink)]/50">Jam Operasional</dt>
                        <dd class="text-sm text-[var(--color-ink)]">{{ $wisata->jam_operasional }}</dd>
                    </div>
                @endif
            </dl>

            {{-- ── Route buttons ── --}}
            @if ($hasMainRoute || $hasAltRoute)
                <div class="mt-8 pt-6 border-t border-[var(--color-bamboo)]">
                    <p class="eyebrow mb-3">Petunjuk Arah</p>

                    @if ($hasBothWaypoints)
                        {{-- Both waypoints set — show two distinct buttons --}}
                        <div class="flex flex-wrap gap-3">
                            <a href="{{ $wisata->main_route_url }}"
                               target="_blank"
                               rel="noopener noreferrer"
                               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-[var(--color-sawah)] hover:bg-[var(--color-sawah-deep)] text-[var(--color-paper)] text-sm font-semibold transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 6.75V15m6-6v8.25m.503 3.498l4.875-2.437c.381-.19.622-.58.622-1.006V4.82c0-.836-.88-1.38-1.628-1.006l-3.869 1.934c-.317.159-.69.159-1.006 0L9.503 3.252a1.125 1.125 0 00-1.006 0L3.622 5.689C3.24 5.88 3 6.27 3 6.695V19.18c0 .836.88 1.38 1.628 1.006l3.869-1.934c.317-.159.69-.159 1.006 0l4.994 2.497z" />
                                </svg>
                                Rute Utama
                            </a>
                            <a href="{{ $wisata->alt_route_url }}"
                               target="_blank"
                               rel="noopener noreferrer"
                               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border-2 border-[var(--color-sawah)] text-[var(--color-sawah)] hover:bg-[var(--color-sawah)] hover:text-[var(--color-paper)] text-sm font-semibold transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 6.75V15m6-6v8.25m.503 3.498l4.875-2.437c.381-.19.622-.58.622-1.006V4.82c0-.836-.88-1.38-1.628-1.006l-3.869 1.934c-.317.159-.69.159-1.006 0L9.503 3.252a1.125 1.125 0 00-1.006 0L3.622 5.689C3.24 5.88 3 6.27 3 6.695V19.18c0 .836.88 1.38 1.628 1.006l3.869-1.934c-.317-.159.69-.159 1.006 0l4.994 2.497z" />
                                </svg>
                                Rute Alternatif
                            </a>
                        </div>
                        <p class="mt-2 text-xs text-[var(--color-ink)]/50">
                            Rute utama disarankan untuk kendaraan roda 4 atau lebih.<br> Rute alternatif hanya dapat diakses oleh kendaraan bermotor.
                        </p>

                    @elseif ($wisata->main_route_lat)
                        {{-- Only main waypoint set --}}
                        <a href="{{ $wisata->main_route_url }}"
                           target="_blank"
                           rel="noopener noreferrer"
                           class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-[var(--color-sawah)] hover:bg-[var(--color-sawah-deep)] text-[var(--color-paper)] text-sm font-semibold transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 6.75V15m6-6v8.25m.503 3.498l4.875-2.437c.381-.19.622-.58.622-1.006V4.82c0-.836-.88-1.38-1.628-1.006l-3.869 1.934c-.317.159-.69.159-1.006 0L9.503 3.252a1.125 1.125 0 00-1.006 0L3.622 5.689C3.24 5.88 3 6.27 3 6.695V19.18c0 .836.88 1.38 1.628 1.006l3.869-1.934c-.317-.159.69-.159 1.006 0l4.994 2.497z" />
                            </svg>
                            Buka di Google Maps
                        </a>

                    @else
                        {{-- No waypoints — direct destination link --}}
                        <a href="{{ $wisata->main_route_url }}"
                           target="_blank"
                           rel="noopener noreferrer"
                           class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-[var(--color-sawah)] hover:bg-[var(--color-sawah-deep)] text-[var(--color-paper)] text-sm font-semibold transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                            </svg>
                            Petunjuk Arah
                        </a>
                    @endif
                </div>
            @endif

            {{-- Contacts --}}
            @if ($wisata->contacts->isNotEmpty())
                <div class="mt-8 pt-6 border-t border-[var(--color-bamboo)]">
                    <p class="eyebrow mb-4">Informasi Kontak</p>
                    <x-contact-list :contacts="$wisata->contacts" />
                </div>
            @endif

            <div class="mt-10 pt-8 border-t border-[var(--color-bamboo)]">
                <a href="{{ route('wisata.index') }}"
                   class="text-sm font-medium text-[var(--color-sawah)] hover:text-[var(--color-bata)] transition-colors">
                    &larr; Kembali ke Destinasi Wisata
                </a>
            </div>
        </div>


        {{-- Left: map --}}
        <div class="lg:col-span-2">
            @if (count($mapMarkers) > 0)
                <x-map
                    :markers="$mapMarkers"
                    height="400px"
                    :zoom="15"
                />
                @if ($wisata->main_route_lat || $wisata->alt_route_lat)
                    <p class="mt-2 text-xs text-[var(--color-ink)]/50 flex items-center gap-1.5">
                        <span class="inline-block w-2.5 h-2.5 rounded-full bg-[var(--color-sawah)]"></span> Lokasi wisata
                        @if ($wisata->main_route_lat)
                            <span class="ml-2 inline-block w-2.5 h-2.5 rounded-full bg-[var(--color-bata)]"></span> Titik masuk
                        @endif
                        @if ($wisata->alt_route_lat)
                            <span class="ml-2 inline-block w-2.5 h-2.5 rounded-full bg-amber-500"></span> Rute alternatif
                        @endif
                    </p>
                @endif
            @endif
        </div>
    </div>

    {{-- Related posts --}}
    @if ($wisata->posts->isNotEmpty())
        <div class="mt-16 pt-12 border-t border-[var(--color-bamboo)]">
            <x-section-heading
                eyebrow="Informasi Terkait"
                title="Berita tentang {{ $wisata->nama }}"
                :href="route('post.index')"
                linkLabel="Semua berita"
            />
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($wisata->posts as $post)
                    <x-card
                        :href="route('post.show', $post->slug)"
                        :image="$post->media->first()?->path ?? $post->image"
                        :eyebrow="$post->tags->isNotEmpty() ? $post->tags->first()->nama : 'Berita'"
                        :title="$post->judul"
                        :excerpt="$post->excerpt"
                        :meta="$post->published_at?->translatedFormat('d F Y')"
                    />
                @endforeach
            </div>
        </div>
    @endif

</div>
@endsection
