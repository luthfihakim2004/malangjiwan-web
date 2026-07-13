@extends('layouts.app')

@section('title', $wisata->nama . ' — Wisata Malangjiwan')
@section('meta_description', Str::limit($wisata->deskripsi, 160))

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-14">

    <nav class="mb-8 text-sm text-[var(--color-ink)]/50 flex items-center gap-2">
        <a href="{{ route('home') }}" class="hover:text-[var(--color-bata)]">Beranda</a>
        <span>/</span>
        <a href="{{ route('wisata.index') }}" class="hover:text-[var(--color-bata)]">Wisata</a>
        <span>/</span>
        <span class="text-[var(--color-ink)]/80 line-clamp-1">{{ $wisata->nama }}</span>
    </nav>

    {{-- Gallery slider — full width at top --}}
    <div class="mb-10">
        <x-gallery-slider
            :media="$wisata->media"
            :fallback="$wisata->image"
            aspect="aspect-video"
            :alt="$wisata->nama"
        />
    </div>

    <div class="grid lg:grid-cols-5 gap-10">

        {{-- Left: map --}}
        <div class="lg:col-span-2">
            @if ($wisata->latitude && $wisata->longitude)
                <x-map
                    :markers="[[
                        'lat'      => $wisata->latitude,
                        'lng'      => $wisata->longitude,
                        'nama'     => $wisata->nama,
                        'type'     => 'wisata',
                        'kategori' => $wisata->tags->pluck('nama')->join(', '),
                        'url'      => null,
                    ]]"
                    height="300px"
                    :zoom="16"
                />
            @endif
        </div>

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
    </div>
</div>
@endsection
