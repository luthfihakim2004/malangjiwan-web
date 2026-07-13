@props([
    'eyebrow' => null,
    'title'   => '',
    'href'    => null,
    'linkLabel' => 'Lihat semua',
])

<div class="flex items-end justify-between gap-4 mb-8">
    <div>
        @if ($eyebrow)
            <p class="eyebrow mb-1">{{ $eyebrow }}</p>
        @endif
        <h2 class="font-display text-2xl sm:text-3xl font-semibold text-[var(--color-sawah-deep)]">
            {{ $title }}
        </h2>
    </div>

    @if ($href)
        <a href="{{ $href }}"
           class="shrink-0 text-sm font-medium text-[var(--color-sawah)] hover:text-[var(--color-bata)] transition-colors">
            {{ $linkLabel }} &rarr;
        </a>
    @endif
</div>
