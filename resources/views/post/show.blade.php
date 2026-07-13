@extends('layouts.app')

@section('title', $post->judul . ' — Malangjiwan')
@section('meta_description', $post->excerpt ?? Str::limit(strip_tags($post->body), 160))

@section('content')
<article class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-14">

    <nav class="mb-8 text-sm text-[var(--color-ink)]/50 flex items-center gap-2">
        <a href="{{ route('home') }}" class="hover:text-[var(--color-bata)]">Beranda</a>
        <span>/</span>
        <a href="{{ route('post.index') }}" class="hover:text-[var(--color-bata)]">Berita</a>
        <span>/</span>
        <span class="text-[var(--color-ink)]/80 line-clamp-1">{{ $post->judul }}</span>
    </nav>

    @if ($post->tags->isNotEmpty())
        <div class="flex flex-wrap gap-2 mb-4">
            @foreach ($post->tags as $tag)
                <a href="{{ route('post.index', ['tag' => $tag->slug]) }}"
                   class="eyebrow px-3 py-1 rounded-full bg-[var(--color-bamboo)] hover:bg-[var(--color-bamboo-dark)] transition-colors">
                    {{ $tag->nama }}
                </a>
            @endforeach
        </div>
    @endif

    <h1 class="font-display text-3xl sm:text-4xl font-semibold text-[var(--color-sawah-deep)] leading-tight">
        {{ $post->judul }}
    </h1>

    <p class="mt-3 text-sm text-[var(--color-ink)]/50">
        {{ $post->published_at?->translatedFormat('d F Y') }}
    </p>

    {{-- Gallery slider --}}
    <div class="mt-8">
        <x-gallery-slider
            :media="$post->media"
            :fallback="$post->image"
            aspect="aspect-video"
            :alt="$post->judul"
        />
    </div>

    {{-- Body --}}
    <div class="mt-10 leading-relaxed text-justify hyphens-auto
                [&_h2]:font-display [&_h2]:text-2xl [&_h2]:font-semibold [&_h2]:text-[var(--color-sawah-deep)] [&_h2]:mt-8 [&_h2]:mb-3 [&_h2]:text-left
                [&_h3]:font-display [&_h3]:text-xl [&_h3]:font-semibold [&_h3]:text-[var(--color-sawah-deep)] [&_h3]:mt-6 [&_h3]:mb-2 [&_h3]:text-left
                [&_a]:text-[var(--color-sawah)] [&_a]:underline [&_a:hover]:text-[var(--color-bata)]
                [&_ul]:list-disc [&_ul]:pl-5 [&_ul]:space-y-1 [&_ul]:text-left
                [&_ol]:list-decimal [&_ol]:pl-5 [&_ol]:space-y-1 [&_ol]:text-left
                [&_img]:rounded-xl [&_img]:my-6 [&_img]:w-full
                [&_blockquote]:border-l-4 [&_blockquote]:border-[var(--color-bata)] [&_blockquote]:pl-4 [&_blockquote]:italic [&_blockquote]:text-[var(--color-ink)]/70 [&_blockquote]:text-left
                [&_p]:mb-4" lang="id">
        {!! $post->body !!}
    </div>

    <div class="mt-14 pt-8 border-t border-[var(--color-bamboo)]">
        <a href="{{ route('post.index') }}"
           class="text-sm font-medium text-[var(--color-sawah)] hover:text-[var(--color-bata)] transition-colors">
            &larr; Kembali ke Berita
        </a>
    </div>

</article>
@endsection
