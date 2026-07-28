@extends('layouts.app')

@section('title', $species->nama_lokal . ' - Vegetasi Malangjiwan')
@section('meta_description', Str::limit(strip_tags($species->deskripsi ?? ''), 160))

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-14">

    <nav class="mb-8 text-sm text-[var(--color-ink)]/50 flex items-center gap-2">
        <a href="{{ route('home') }}" class="hover:text-[var(--color-bata)]">Beranda</a>
        <span>/</span>
        <a href="{{ route('vegetasi.index') }}" class="hover:text-[var(--color-bata)]">Vegetasi</a>
        <span>/</span>
        <span class="text-[var(--color-ink)]/80">{{ $species->nama_lokal }}</span>
    </nav>

    <div class="grid md:grid-cols-2 gap-10">

        {{-- Image --}}
        <div>
            @if ($species->image)
                <div class="rounded-2xl overflow-hidden aspect-square">
                    <img src="{{ asset('storage/' . $species->image) }}"
                         alt="{{ $species->nama_lokal }}"
                         class="w-full h-full object-cover">
                </div>
            @else
                <div class="rounded-2xl aspect-square bg-[var(--color-bamboo)] flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 text-[var(--color-bamboo-dark)]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386l-1.591 1.591M21 12h-2.25m-.386 6.364l-1.591-1.591M12 18.75V21m-4.773-4.227l-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z" />
                    </svg>
                </div>
            @endif
        </div>

        {{-- Info --}}
        <div>
            @if ($species->wisata)
                <p class="eyebrow mb-2">{{ $species->wisata->nama }}</p>
            @else
                <p class="eyebrow mb-2">Vegetasi Desa</p>
            @endif

            <h1 class="font-display text-3xl sm:text-4xl font-semibold text-[var(--color-sawah-deep)] leading-tight">
                {{ $species->nama_lokal }}
            </h1>

            @if ($species->nama_ilmiah)
                <p class="mt-1 text-lg italic text-[var(--color-ink)]/50">
                    {{ $species->nama_ilmiah }}
                </p>
            @endif

            @if ($species->deskripsi)
                <div class="mt-6 prose-village text-justify hyphens-auto
                            [&_p]:mb-3 [&_ul]:list-disc [&_ul]:pl-5 [&_ul]:text-left
                            [&_ol]:list-decimal [&_ol]:pl-5 [&_ol]:text-left
                            [&_strong]:font-semibold
                            [&_a]:text-[var(--color-sawah)] [&_a]:underline
                            text-[var(--color-ink)]/80" lang="id">
                    {!! $species->deskripsi !!}
                </div>
            @endif

            @if ($species->fun_fact)
                <div class="mt-6 p-4 rounded-xl bg-[var(--color-sawah)]/8 border border-[var(--color-sawah)]/20">
                    <p class="eyebrow text-[var(--color-sawah)] mb-1">Tahukah kamu?</p>
                    <p class="text-sm text-[var(--color-ink)]/80 leading-relaxed">{{ $species->fun_fact }}</p>
                </div>
            @endif

            {{-- Link to wisata if associated --}}
            @if ($species->wisata)
                <div class="mt-8 pt-6 border-t border-[var(--color-bamboo)]">
                    <p class="text-sm text-[var(--color-ink)]/50 mb-3">Tumbuhan ini ditemukan di:</p>
                    <a href="{{ route('wisata.show', $species->wisata->slug) }}"
                       class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border border-[var(--color-sawah)] text-[var(--color-sawah)] hover:bg-[var(--color-sawah)] hover:text-[var(--color-paper)] text-sm font-semibold transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                        </svg>
                        {{ $species->wisata->nama }}
                    </a>
                </div>
            @endif
        </div>
    </div>

    <div class="mt-12 pt-8 border-t border-[var(--color-bamboo)]">
        <a href="{{ route('vegetasi.index') }}"
           class="text-sm font-medium text-[var(--color-sawah)] hover:text-[var(--color-bata)] transition-colors">
            &larr; Kembali ke Inventaris Vegetasi
        </a>
    </div>

</div>
@endsection
