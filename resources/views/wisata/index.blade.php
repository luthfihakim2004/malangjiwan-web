@extends('layouts.app')

@section('title', 'Wisata Desa Malangjiwan, Klaten')
@section('meta_description', 'Jelajahi destinasi wisata unggulan di Desa Malangjiwan.')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-14">

    <div class="mb-10">
        <p class="eyebrow mb-1">Jelajahi Desa</p>
        <h1 class="font-display text-3xl sm:text-4xl font-semibold text-[var(--color-sawah-deep)]">Destinasi Wisata</h1>
        <p class="mt-2 text-[var(--color-ink)]/60 max-w-lg">Temukan keindahan alam dan budaya Desa Malangjiwan.</p>
    </div>

    @if ($tags->isNotEmpty())
        <div class="mb-8">
            <x-tag-filter :tags="$tags" :activeTag="$activeTag" :baseUrl="route('wisata.index')" />
        </div>
    @endif

    @if ($wisatas->isNotEmpty())
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
        <div class="mt-10">{{ $wisatas->links() }}</div>
    @else
        <div class="py-24 text-center text-[var(--color-ink)]/50">
            <p class="font-display text-xl">Belum ada destinasi{{ $activeTag ? ' dengan tag ini' : '' }}.</p>
        </div>
    @endif

</div>
@endsection
