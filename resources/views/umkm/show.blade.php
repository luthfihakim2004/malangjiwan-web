@extends('layouts.app')

@section('title', $umkm->nama . ' — UMKM Malangjiwan')
@section('meta_description', Str::limit($umkm->deskripsi, 160))

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-14">

    <nav class="mb-8 text-sm text-[var(--color-ink)]/50 flex items-center gap-2">
        <a href="{{ route('home') }}" class="hover:text-[var(--color-bata)]">Beranda</a>
        <span>/</span>
        <a href="{{ route('umkm.index') }}" class="hover:text-[var(--color-bata)]">UMKM</a>
        <span>/</span>
        <span class="text-[var(--color-ink)]/80 line-clamp-1">{{ $umkm->nama }}</span>
    </nav>

    {{-- Gallery slider — full width at top --}}
    <div class="mb-10">
        <x-gallery-slider
            :media="$umkm->media"
            :fallback="$umkm->image"
            aspect="aspect-video"
            :alt="$umkm->nama"
        />
    </div>

    <div class="grid lg:grid-cols-5 gap-10">

        {{-- Left: map --}}
        <div class="lg:col-span-2">
            @if ($umkm->latitude && $umkm->longitude)
                <x-map
                    :markers="[[
                        'lat'      => $umkm->latitude,
                        'lng'      => $umkm->longitude,
                        'nama'     => $umkm->nama,
                        'type'     => 'umkm',
                        'kategori' => $umkm->tags->pluck('nama')->join(', '),
                        'url'      => null,
                    ]]"
                    height="300px"
                    :zoom="16"
                />
            @endif
        </div>

        {{-- Right: info --}}
        <div class="lg:col-span-3">

            @if ($umkm->tags->isNotEmpty())
                <div class="flex flex-wrap gap-2 mb-3">
                    @foreach ($umkm->tags as $tag)
                        <a href="{{ route('umkm.index', ['tag' => $tag->slug]) }}"
                           class="eyebrow px-3 py-1 rounded-full bg-[var(--color-bamboo)] hover:bg-[var(--color-bamboo-dark)] transition-colors">
                            {{ $tag->nama }}
                        </a>
                    @endforeach
                </div>
            @endif

            <h1 class="font-display text-3xl sm:text-4xl font-semibold text-[var(--color-sawah-deep)] leading-tight">
                {{ $umkm->nama }}
            </h1>

            @if ($umkm->owner)
                <p class="mt-1 text-sm text-[var(--color-ink)]/60">Pemilik: {{ $umkm->owner }}</p>
            @endif

            @if ($umkm->deskripsi)
                <p class="mt-6 text-[var(--color-ink)]/80 leading-relaxed text-justify hyphens-auto" lang="id">
                    {{ $umkm->deskripsi }}
                </p>
            @endif

            <dl class="mt-8 space-y-4">
                @if ($umkm->alamat)
                    <div class="flex gap-3">
                        <dt class="shrink-0 w-36 text-sm font-medium text-[var(--color-ink)]/50">Alamat</dt>
                        <dd class="text-sm text-[var(--color-ink)]">{{ $umkm->alamat }}</dd>
                    </div>
                @endif
                @if ($umkm->jam_operasional)
                    <div class="flex gap-3">
                        <dt class="shrink-0 w-36 text-sm font-medium text-[var(--color-ink)]/50">Jam Operasional</dt>
                        <dd class="text-sm text-[var(--color-ink)]">{{ $umkm->jam_operasional }}</dd>
                    </div>
                @endif
            </dl>

            @if ($umkm->contacts->isNotEmpty())
                <div class="mt-8 pt-6 border-t border-[var(--color-bamboo)]">
                    <p class="eyebrow mb-4">Hubungi Kami</p>
                    <x-contact-list :contacts="$umkm->contacts" />
                </div>
            @endif

            <div class="mt-10 pt-8 border-t border-[var(--color-bamboo)]">
                <a href="{{ route('umkm.index') }}"
                   class="text-sm font-medium text-[var(--color-sawah)] hover:text-[var(--color-bata)] transition-colors">
                    &larr; Kembali ke Direktori UMKM
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
