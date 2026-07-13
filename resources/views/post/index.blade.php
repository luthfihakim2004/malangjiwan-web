@extends('layouts.app')

@section('title', 'Berita Desa — Malangjiwan')
@section('meta_description', 'Informasi dan berita terkini dari Desa Malangjiwan.')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-14">

    <div class="mb-10">
        <p class="eyebrow mb-1">Informasi Desa</p>
        <h1 class="font-display text-3xl sm:text-4xl font-semibold text-[var(--color-sawah-deep)]">Berita & Pengumuman</h1>
    </div>

    @if ($tags->isNotEmpty())
        <div class="mb-8">
            <x-tag-filter :tags="$tags" :activeTag="$activeTag" :baseUrl="route('post.index')" />
        </div>
    @endif

    @if ($posts->isNotEmpty())
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
        <div class="mt-10">{{ $posts->links() }}</div>
    @else
        <div class="py-24 text-center text-[var(--color-ink)]/50">
            <p class="font-display text-xl">Belum ada berita{{ $activeTag ? ' dengan tag ini' : '' }}.</p>
        </div>
    @endif

</div>
@endsection
