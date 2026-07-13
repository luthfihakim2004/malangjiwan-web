@extends('layouts.app')

@section('title', 'Beranda — Desa Malangjiwan')
@section('meta_description', 'Selamat datang di portal resmi Desa Malangjiwan. Temukan destinasi wisata, UMKM lokal, dan berita terkini dari desa kami.')

@section('content')

    {{-- ============================================================
         HERO
    ============================================================ --}}
    <section class="relative overflow-hidden min-h-[520px] sm:min-h-[600px] flex items-end">

        {{-- Background image --}}
        @if ($profil->hero_image)
            <img src="{{ asset('storage/' . $profil->hero_image) }}"
                 alt="Desa Malangjiwan"
                 class="absolute inset-0 w-full h-full object-cover object-center">
        @else
            <div class="absolute inset-0 bg-[var(--color-sawah-deep)]"></div>
        @endif

        {{-- Gradient overlay — dark at bottom where text sits, subtle at top --}}
        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-black/10"></div>

        {{-- Content — sits at the bottom of the image --}}
        <div class="relative w-full max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-16 sm:py-20">
            <p class="eyebrow text-white/60 mb-3">Desa Malangjiwan, Kec. Kebonarum, Kab. Klaten, Jawa Tengah</p>
            <h1 class="font-display text-4xl sm:text-5xl font-semibold text-white leading-tight max-w-2xl">
                Selamat datang di Desa Malangjiwan
            </h1>
            <p class="mt-4 text-white/70 leading-relaxed max-w-lg">
                Temukan wisata, produk UMKM unggulan, dan informasi terkini dari jantung desa kami.
            </p>
            <div class="mt-8 flex flex-wrap gap-3">
                <a href="{{ route('wisata.index') }}"
                   class="px-5 py-2.5 rounded-full bg-[var(--color-bata)] text-white text-sm font-semibold hover:bg-[var(--color-bata-dim)] transition-colors">
                    Jelajahi Wisata
                </a>
                <a href="{{ route('umkm.index') }}"
                   class="px-5 py-2.5 rounded-full border border-white/30 text-white text-sm font-semibold hover:bg-white/10 transition-colors">
                    Direktori UMKM
                </a>
            </div>
        </div>
    </section>

    {{-- ============================================================
         FEATURED WISATA
    ============================================================ --}}
    @if ($wisatas->isNotEmpty())
        <section class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 mt-20">
            <x-section-heading
                eyebrow="Destinasi Unggulan"
                title="Wisata Desa Malangjiwan"
                :href="route('wisata.index')"
            />
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($wisatas as $wisata)
                    <x-card
                        :href="route('wisata.show', $wisata->slug)"
                        :image="$wisata->media->first()?->path"
                        :eyebrow="'Wisata' . ($wisata->tags->isNotEmpty() ? ' · ' . $wisata->tags->first()->nama : '')"
                        :title="$wisata->nama"
                        :excerpt="$wisata->deskripsi"
                        :meta="$wisata->jam_operasional"
                    />
                @endforeach
            </div>
        </section>
    @endif

    {{-- ============================================================
         FEATURED UMKM
    ============================================================ --}}
    @if ($umkms->isNotEmpty())
        <section class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 mt-20">
            <x-section-heading
                eyebrow="UMKM Unggulan"
                title="UMKM Desa Malangjiwan"
                :href="route('umkm.index')"
            />
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($umkms as $umkm)
                    <x-card
                        :href="route('umkm.show', $umkm->slug)"
                        :image="$umkm->media->first()?->path"
                        :eyebrow="'UMKM' . ($umkm->tags->isNotEmpty() ? ' · ' . $umkm->tags->first()->nama : '')"
                        :title="$umkm->nama"
                        :excerpt="$umkm->deskripsi"
                        :meta="$umkm->jam_operasional"
                    />
                @endforeach
            </div>
        </section>
    @endif

    {{-- ============================================================
         LATEST POSTS
    ============================================================ --}}
    @if ($posts->isNotEmpty())
        <section class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 mt-20">
            <x-section-heading
                eyebrow="Informasi Terkini"
                title="Berita Desa"
                :href="route('post.index')"
            />
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($posts as $post)
                    <x-card
                        :href="route('post.show', $post->slug)"
                        :image="$post->media->first()?->path"
                        :eyebrow="$post->tags->isNotEmpty() ? $post->tags->first()->nama : 'Berita'"
                        :title="$post->judul"
                        :excerpt="$post->excerpt"
                        :meta="$post->published_at?->translatedFormat('d F Y')"
                    />
                @endforeach
            </div>
        </section>
    @endif

    {{-- ============================================================
         CTA STRIP — link to combined map
    ============================================================ --}}
    <section class="mt-20 bg-[var(--color-paper-dim)] border-y border-[var(--color-bamboo)]">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-14 flex flex-col sm:flex-row items-center justify-between gap-6">
            <div>
                <p class="eyebrow mb-1">Temukan di Peta</p>
                <h2 class="font-display text-2xl font-semibold text-[var(--color-sawah-deep)]">
                    Semua lokasi wisata & UMKM dalam satu peta
                </h2>
            </div>
            <a href="{{ route('peta') }}"
               class="shrink-0 px-6 py-3 rounded-full bg-[var(--color-sawah)] text-[var(--color-paper)] font-semibold hover:bg-[var(--color-sawah-deep)] transition-colors">
                Buka Peta
            </a>
        </div>
    </section>

@endsection
