@extends('layouts.app')

@section('title', 'Profil Desa Malangjiwan, Kebonarum, Klaten')
@section('meta_description', 'Sejarah, visi, misi, dan informasi resmi ' . $profil->nama_desa . ', Kecamatan Kebonarum, Kabupaten Klaten, Jawa Tengah.')

@section('content')

    {{-- ── Hero ── --}}
    <section class="relative overflow-hidden min-h-[400px] sm:min-h-[480px] flex items-end">

        @if ($profil->foto_kantor)
            <img src="{{ asset('storage/' . $profil->foto_kantor) }}"
                 alt="Kantor Desa {{ $profil->nama_desa }}"
                 class="absolute inset-0 w-full h-full object-cover object-center">
        @else
            <div class="absolute inset-0 bg-[var(--color-sawah-deep)]"></div>
        @endif

        <div class="absolute inset-0 bg-gradient-to-t from-black/75 via-black/35 to-black/10"></div>

        <div class="relative w-full max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12 flex items-end gap-5">
            @if ($profil->logo)
                <img src="{{ asset('storage/' . $profil->logo) }}"
                     alt="Logo {{ $profil->nama_desa }}"
                     class="shrink-0 w-16 h-16 sm:w-20 sm:h-20 object-contain rounded-xl bg-white/10 p-1">
            @endif
            <div>
                <p class="eyebrow text-white/60 mb-2">Profil Resmi</p>
                <h1 class="font-display text-3xl sm:text-4xl font-semibold text-white leading-tight">
                    {{ $profil->nama_desa }}
                </h1>
                @if ($geojson)
                    <p class="mt-1.5 text-sm text-white/60">
                        Kec. {{ $geojson['WADMKC'] }}, Kab. {{ $geojson['WADMKK'] }}, {{ $geojson['WADMPR'] }}
                    </p>
                @elseif ($profil->alamat_kantor)
                    <p class="mt-1.5 text-sm text-white/60">{{ $profil->alamat_kantor }}</p>
                @endif
            </div>
        </div>
    </section>

    {{-- ── Official data strip ── --}}
    @if ($geojson)
        <div class="bg-[var(--color-sawah-deep)] text-[var(--color-paper)]">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                <div class="flex flex-wrap items-center gap-x-6 gap-y-1 text-xs text-[var(--color-paper)]/60">
                    <span>Kode BPS: <span class="text-[var(--color-paper)]/90 font-medium">{{ $geojson['KDEBPS'] }}</span></span>
                    <span class="hidden sm:inline opacity-30">·</span>
                    <span>Kode Kemendagri: <span class="text-[var(--color-paper)]/90 font-medium">{{ $geojson['KDEPUM'] }}</span></span>
                    <span class="hidden sm:inline opacity-30">·</span>
                    <span>Status: <span class="text-[var(--color-paper)]/90 font-medium">{{ $geojson['TIPADM'] == 1 ? 'Desa' : 'Kelurahan' }}</span></span>
                    <span class="hidden sm:inline opacity-30">·</span>
                    <span>Batas wilayah berdasarkan Ajudikasi {{ $geojson['UUPP'] }}</span>
                </div>
            </div>
        </div>
    @endif

    {{-- ── Body ── --}}
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-14">

        {{-- Stats row from GeoJSON --}}
        @if ($geojson)
            @php
                $luasKm2 = number_format($geojson['luas'], 2);
            @endphp
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-14">
                <div class="p-5 rounded-2xl bg-[var(--color-paper-dim)] border border-[var(--color-bamboo)]">
                    <p class="font-display text-2xl font-semibold text-[var(--color-sawah-deep)]">{{ $luasKm2 }} km²</p>
                    <p class="mt-1 text-xs text-[var(--color-ink)]/50">Luas Wilayah</p>
                </div>
                <div class="p-5 rounded-2xl bg-[var(--color-paper-dim)] border border-[var(--color-bamboo)]">
                    <p class="font-display text-2xl font-semibold text-[var(--color-sawah-deep)]">{{ $geojson['WADMKC'] }}</p>
                    <p class="mt-1 text-xs text-[var(--color-ink)]/50">Kecamatan</p>
                </div>
                <div class="p-5 rounded-2xl bg-[var(--color-paper-dim)] border border-[var(--color-bamboo)]">
                    <p class="font-display text-2xl font-semibold text-[var(--color-sawah-deep)]">{{ $geojson['WADMKK'] }}</p>
                    <p class="mt-1 text-xs text-[var(--color-ink)]/50">Kabupaten</p>
                </div>
                <div class="p-5 rounded-2xl bg-[var(--color-paper-dim)] border border-[var(--color-bamboo)]">
                    <p class="font-display text-2xl font-semibold text-[var(--color-sawah-deep)]">{{ $geojson['WADMPR'] }}</p>
                    <p class="mt-1 text-xs text-[var(--color-ink)]/50">Provinsi</p>
                </div>
            </div>
        @endif

        {{-- Content sections --}}
        @foreach ([
            ['label' => 'Sejarah Desa',       'key' => 'sejarah'],
            ['label' => 'Visi',                'key' => 'visi'],
            ['label' => 'Misi',                'key' => 'misi'],
            ['label' => 'Struktur Organisasi', 'key' => 'struktur_organisasi'],
        ] as $section)
            @if ($profil->{$section['key']})
                <section class="mb-12">
                    <h2 class="font-display text-2xl font-semibold text-[var(--color-sawah-deep)] mb-4 pb-3 border-b border-[var(--color-bamboo)]">
                        {{ $section['label'] }}
                    </h2>
                    <div class="prose-village text-justify hyphens-auto
                                [&_ul]:list-disc [&_ul]:pl-5 [&_ul]:space-y-1 [&_ul]:text-left
                                [&_ol]:list-decimal [&_ol]:pl-5 [&_ol]:space-y-1 [&_ol]:text-left
                                [&_a]:text-[var(--color-sawah)] [&_a]:underline
                                [&_strong]:font-semibold
                                [&_p]:mb-3" lang="id">
                        {!! $profil->{$section['key']} !!}
                    </div>
                </section>
            @endif
        @endforeach

        {{-- Kontak + Map --}}
        @if ($profil->contacts->isNotEmpty() || ($profil->latitude && $profil->longitude))
            <div class="grid md:grid-cols-2 gap-8 mt-4">

                @if ($profil->contacts->isNotEmpty())
                    <div class="p-6 rounded-2xl bg-[var(--color-paper-dim)] border border-[var(--color-bamboo)]">
                        <h2 class="font-display text-xl font-semibold text-[var(--color-sawah-deep)] mb-2">
                            Kontak Desa
                        </h2>
                        @if ($profil->alamat_kantor)
                            <p class="text-sm text-[var(--color-ink)]/60 mb-5">{{ $profil->alamat_kantor }}</p>
                        @endif
                        <x-contact-list :contacts="$profil->contacts" />
                    </div>
                @endif

                @if ($profil->latitude && $profil->longitude)
                    <div>
                        <h2 class="font-display text-xl font-semibold text-[var(--color-sawah-deep)] mb-4">
                            Lokasi Kantor Desa
                        </h2>
                        <x-map
                            :markers="[[
                                'lat'      => (float) $profil->latitude,
                                'lng'      => (float) $profil->longitude,
                                'nama'     => 'Kantor Desa ' . $profil->nama_desa,
                                'type'     => 'wisata',
                                'kategori' => null,
                                'url'      => null,
                            ]]"
                            height="280px"
                            :zoom="16"
                        />
                    </div>
                @endif

            </div>
        @endif

        {{-- Source attribution --}}
        @if ($geojson)
            <p class="mt-14 text-xs text-[var(--color-ink)]/40 text-center">
                Data batas wilayah bersumber dari BIG (Badan Informasi Geospasial) · {{ $geojson['METADATA'] }} · {{ $geojson['SRS_ID'] }}
            </p>
        @endif

    </div>

@endsection
