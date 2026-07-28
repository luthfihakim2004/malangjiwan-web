@extends('layouts.app')

@section('title', 'UMKM Desa Malangjiwan - Produk dan Usaha Lokal')
@section('meta_description', 'Temukan produk dan usaha lokal dari UMKM Desa Malangjiwan.')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-14">

    <div class="mb-10">
        <p class="eyebrow mb-1">Produk Lokal</p>
        <h1 class="font-display text-3xl sm:text-4xl font-semibold text-[var(--color-sawah-deep)]">Daftar UMKM</h1>
        <p class="mt-2 text-[var(--color-ink)]/60 max-w-lg">Dukung produk dan usaha warga Desa Malangjiwan.</p>
    </div>

    @if ($tags->isNotEmpty())
        <div class="mb-8">
            <x-tag-filter :tags="$tags" :activeTag="$activeTag" :baseUrl="route('umkm.index')" />
        </div>
    @endif

    @if ($umkms->isNotEmpty())
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
        <div class="mt-10">{{ $umkms->links() }}</div>
    @else
        <div class="py-24 text-center text-[var(--color-ink)]/50">
            <p class="font-display text-xl">Belum ada UMKM{{ $activeTag ? ' dengan tag ini' : '' }}.</p>
        </div>
    @endif

</div>
@endsection
