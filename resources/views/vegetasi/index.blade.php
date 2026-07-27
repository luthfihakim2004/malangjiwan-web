@extends('layouts.app')

@section('title', 'Inventaris Vegetasi — Malangjiwan')
@section('meta_description', 'Direktori spesies tumbuhan yang ditemukan di Desa Malangjiwan.')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-14">

    <div class="mb-10">
        <p class="eyebrow mb-1">Inventaris Digital</p>
        <h1 class="font-display text-3xl sm:text-4xl font-semibold text-[var(--color-sawah-deep)]">
            Vegetasi Desa Malangjiwan
        </h1>
        <p class="mt-2 text-[var(--color-ink)]/60 max-w-lg">
            Daftar spesies tumbuhan yang terdapat di wilayah Desa Malangjiwan.
            Pindai QR code pada setiap pohon untuk melihat informasi lengkapnya.
        </p>
    </div>

    @if ($species->isNotEmpty())
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($species as $sp)
                <a href="{{ route('vegetasi.show', $sp->slug) }}"
                   class="group flex flex-col bg-[var(--color-paper)] border border-[var(--color-bamboo)] rounded-2xl overflow-hidden hover:border-[var(--color-sawah-light)] hover:shadow-md transition-all duration-200">

                    {{-- Image --}}
                    <div class="aspect-[4/3] overflow-hidden bg-[var(--color-bamboo)]">
                        @if ($sp->image)
                            <img src="{{ asset('storage/' . $sp->image) }}"
                                 alt="{{ $sp->nama_lokal }}"
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-[var(--color-bamboo-dark)]">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386l-1.591 1.591M21 12h-2.25m-.386 6.364l-1.591-1.591M12 18.75V21m-4.773-4.227l-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z" />
                                </svg>
                            </div>
                        @endif
                    </div>

                    <div class="p-5 flex flex-col flex-1">
                        @if ($sp->wisata)
                            <p class="eyebrow mb-1">{{ $sp->wisata->nama }}</p>
                        @endif
                        <h2 class="font-display text-xl font-semibold text-[var(--color-sawah-deep)] group-hover:text-[var(--color-sawah)] transition-colors">
                            {{ $sp->nama_lokal }}
                        </h2>
                        @if ($sp->nama_ilmiah)
                            <p class="mt-0.5 text-sm italic text-[var(--color-ink)]/50">{{ $sp->nama_ilmiah }}</p>
                        @endif
                        @if ($sp->deskripsi)
                            <p class="mt-3 text-sm text-[var(--color-ink)]/70 line-clamp-2 flex-1">
                                {{ Str::limit(strip_tags($sp->deskripsi), 120) }}
                            </p>
                        @endif
                    </div>
                </a>
            @endforeach
        </div>

        <div class="mt-10">{{ $species->links() }}</div>
    @else
        <div class="py-24 text-center text-[var(--color-ink)]/50">
            <p class="font-display text-xl">Belum ada data vegetasi.</p>
        </div>
    @endif

</div>
@endsection
