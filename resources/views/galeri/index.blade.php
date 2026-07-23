@extends('layouts.app')
@section('title', 'Galeri Desa Malangjiwan')
@section('meta_description', 'Galeri foto wisata, UMKM, dan kegiatan Desa Malangjiwan.')
@section('canonical', route('galeri.index'))

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
    <div class="mb-10">
        <p class="eyebrow mb-1">Dokumentasi</p>
        <h1 class="font-display text-3xl sm:text-4xl font-semibold text-[var(--color-sawah-deep)]">Galeri Foto</h1>
        <p class="mt-2 text-[var(--color-ink)]/60 max-w-lg">Dokumentasi wisata, UMKM, dan kegiatan Desa Malangjiwan.</p>
    </div>

    {{-- Type filter --}}
    <div class="flex flex-wrap gap-2 mb-10">
        @php
            $tipes = [
                null     => 'Semua',
                'wisata' => 'Wisata',
                'umkm'   => 'UMKM',
                'post'   => 'Berita',
            ];
        @endphp
        @foreach ($tipes as $val => $label)
            <a href="{{ route('galeri.index', $val ? ['tipe' => $val] : []) }}"
               class="px-4 py-1.5 rounded-2xl text-sm font-medium border transition-all
                      {{ $activeTipe === $val
                         ? 'bg-[var(--color-sawah-deep)] text-[var(--color-paper)] border-[var(--color-sawah-deep)]'
                         : 'border-[var(--color-bamboo-dark)] text-[var(--color-ink)]/70 hover:border-[var(--color-sawah-light)]' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>

    @if ($media->isNotEmpty())

        @php
            $slides = $media->map(function ($item) {
                $parent = $item->mediable;
                if (!$parent) return null;

                $tipeItem = match(true) {
                    $parent instanceof \App\Models\Wisata => 'wisata',
                    $parent instanceof \App\Models\Umkm   => 'umkm',
                    $parent instanceof \App\Models\Post   => 'post',
                    default => null,
                };

                $parentName = match(true) {
                    $parent instanceof \App\Models\Post => $parent->judul ?? '',
                    default => $parent->nama ?? '',
                };

                $parentUrl = match($tipeItem) {
                    'wisata' => route('wisata.show', $parent->slug),
                    'umkm'   => route('umkm.show', $parent->slug),
                    'post'   => route('post.show', $parent->slug),
                    default  => '#',
                };

                return [
                    'src'     => asset('storage/' . $item->path),
                    'caption' => $item->caption ?? '',
                    'title'   => $parentName,
                    'url'     => $parentUrl,
                ];
            })->filter()->values();
        @endphp

        <div
            x-data="{
                isOpen: false,
                current: 0,
                slides: {{ Js::from($slides) }},

                get slide() { return this.slides[this.current] ?? {}; },

                show(index) {
                    this.current = index;
                    this.isOpen = true;
                    document.body.style.overflow = 'hidden';
                    document.querySelectorAll('[data-village-map]').forEach(el => {
                        el.style.visibility = 'hidden';
                    });
                },
                close() {
                    this.isOpen = false;
                    document.body.style.overflow = '';
                    document.querySelectorAll('[data-village-map]').forEach(el => {
                        el.style.visibility = 'visible';
                    });
                },
                prev() {
                    this.current = (this.current - 1 + this.slides.length) % this.slides.length;
                },
                next() {
                    this.current = (this.current + 1) % this.slides.length;
                },
            }"
            @keydown.escape.window="close()"
            @keydown.arrow-left.window="isOpen && prev()"
            @keydown.arrow-right.window="isOpen && next()"
        >
            {{-- Masonry grid --}}
            <div class="columns-2 sm:columns-3 lg:columns-4 gap-4 space-y-4">
                @foreach ($media as $index => $item)
                    @php
                        $parent = $item->mediable;
                        if (!$parent) continue;

                        $tipeItem = match(true) {
                            $parent instanceof \App\Models\Wisata => 'wisata',
                            $parent instanceof \App\Models\Umkm   => 'umkm',
                            $parent instanceof \App\Models\Post   => 'post',
                            default => null,
                        };

                        $parentName = match(true) {
                            $parent instanceof \App\Models\Post => $parent->judul ?? '',
                            default => $parent->nama ?? '',
                        };

                        $tipeLabel = match($tipeItem) {
                            'wisata' => 'Wisata',
                            'umkm'   => 'UMKM',
                            'post'   => 'Berita',
                            default  => '',
                        };

                        $tipeColor = match($tipeItem) {
                            'wisata' => 'text-[var(--color-sawah)] bg-[var(--color-sawah)]/10',
                            'umkm'   => 'text-[var(--color-bata)] bg-[var(--color-bata)]/10',
                            'post'   => 'text-[var(--color-sawah-light)] bg-[var(--color-sawah-light)]/10',
                            default  => 'text-[var(--color-ink)]/60 bg-[var(--color-bamboo)]',
                        };
                    @endphp

                    <div class="break-inside-avoid group cursor-zoom-in mb-4"
                         @click="show({{ $index }})">
                        <div class="relative rounded-2xl overflow-hidden bg-[var(--color-bamboo)] border border-[var(--color-bamboo)] hover:border-[var(--color-sawah-light)] transition-all duration-300">
                            <img
                                src="{{ asset('storage/' . $item->path) }}"
                                alt="{{ $item->caption ?? $parentName }}"
                                class="w-full h-auto object-cover transition-transform duration-500 group-hover:scale-105"
                                loading="lazy"
                            >
                            @if ($tipeLabel)
                                <span class="absolute top-3 left-3 text-[10px] px-3 py-1 rounded-full {{ $tipeColor }} backdrop-blur-sm font-medium">
                                    {{ strtoupper($tipeLabel) }}
                                </span>
                            @endif
                        </div>
                        <div class="mt-3 px-1">
                            <p class="font-medium text-[var(--color-sawah-deep)] line-clamp-2">{{ $parentName }}</p>
                            @if ($item->caption)
                                <p class="text-[13px] text-[var(--color-ink)]/70 line-clamp-2 mt-1">{{ $item->caption }}</p>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            <div class="mt-12">
                {{ $media->links() }}
            </div>

            {{-- Lightbox --}}
            <div
                x-show="isOpen"
                x-transition:enter="transition-opacity duration-200"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition-opacity duration-150"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 z-[1000] bg-black/92 flex flex-col items-center justify-center"
                @click.self="close()"
                role="dialog"
                aria-modal="true"
                style="display: none;"
            >
                {{-- Close --}}
                <button @click="close()"
                    class="absolute top-4 right-4 w-10 h-10 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition-colors z-10"
                    aria-label="Tutup">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                {{-- Counter --}}
                <div class="absolute top-4 left-1/2 -translate-x-1/2 bg-white/10 text-white text-sm px-3 py-1.5 rounded-full tabular-nums select-none">
                    <span x-text="current + 1"></span> / <span x-text="slides.length"></span>
                </div>

                {{-- Prev --}}
                <button @click="prev()"
                    class="absolute left-4 top-1/2 -translate-y-1/2 w-12 h-12 rounded-full bg-white/10 hover:bg-white/25 text-white flex items-center justify-center transition-colors z-10"
                    aria-label="Foto sebelumnya">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                    </svg>
                </button>

                {{-- Next --}}
                <button @click="next()"
                    class="absolute right-4 top-1/2 -translate-y-1/2 w-12 h-12 rounded-full bg-white/10 hover:bg-white/25 text-white flex items-center justify-center transition-colors z-10"
                    aria-label="Foto berikutnya">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                    </svg>
                </button>

                {{-- Image + info --}}
                <div class="flex flex-col items-center justify-center px-20 py-16 gap-4 w-full max-h-full">
                    <img
                        :src="slide.src"
                        :alt="slide.caption || slide.title"
                        class="max-w-full max-h-[72vh] object-contain rounded-lg"
                    >
                    <div class="text-center">
                        <p x-show="slide.caption" x-text="slide.caption" class="text-white/70 text-sm mb-2"></p>
                        <p x-show="slide.title" x-text="slide.title" class="text-white font-medium text-sm mb-3"></p>
                        <a :href="slide.url"
                           class="inline-flex items-center gap-1.5 text-sm px-4 py-2 rounded-full bg-[var(--color-sawah)] hover:bg-[var(--color-sawah-deep)] text-white transition-colors">
                            Lihat halaman
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
                            </svg>
                        </a>
                    </div>
                </div>

            </div>
        </div>

    @else
        <div class="py-24 text-center text-[var(--color-ink)]/50">
            <p class="font-display text-xl">Belum ada foto{{ $activeTipe ? ' dalam kategori ini' : '' }}.</p>
        </div>
    @endif
</div>
@endsection
