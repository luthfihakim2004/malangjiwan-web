@props([
    'href'      => '#',
    'image'     => null,
    'eyebrow'   => null,      // e.g. "WISATA · Alam"
    'title'     => '',
    'excerpt'   => null,
    'meta'      => null,      // e.g. jam operasional or published date
])

<a href="{{ $href }}"
   class="group flex flex-col bg-[var(--color-paper)] border border-[var(--color-bamboo)] rounded-2xl overflow-hidden hover:border-[var(--color-sawah-light)] hover:shadow-md transition-all duration-200">

    {{-- Image --}}
    <div class="aspect-[4/3] overflow-hidden bg-[var(--color-bamboo)]">
        @if ($image)
            <img src="{{ asset('storage/' . $image) }}"
                 alt="{{ $title }}"
                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
        @else
            <div class="w-full h-full flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-[var(--color-bamboo-dark)]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909M3.75 18h16.5M3 6.75h18M3 12h18" />
                </svg>
            </div>
        @endif
    </div>

    {{-- Body --}}
    <div class="flex flex-col flex-1 p-5 gap-2">
        @if ($eyebrow)
            <p class="eyebrow">{{ $eyebrow }}</p>
        @endif

        <h3 class="font-display font-semibold text-[var(--color-sawah-deep)] text-lg leading-snug group-hover:text-[var(--color-sawah)] transition-colors line-clamp-2">
            {{ $title }}
        </h3>

        @if ($excerpt)
            <p class="text-sm text-[var(--color-ink)]/70 line-clamp-3 flex-1">{{ $excerpt }}</p>
        @endif

        @if ($meta)
            <p class="text-xs text-[var(--color-ink)]/50 mt-1">{{ $meta }}</p>
        @endif
    </div>
</a>
