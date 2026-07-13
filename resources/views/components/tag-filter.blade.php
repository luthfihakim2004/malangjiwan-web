@props([
    'tags'      => collect(),
    'activeTag' => null,
    'baseUrl'   => '',
])

<div class="flex flex-wrap gap-2">
    <a href="{{ $baseUrl }}"
       class="px-4 py-1.5 rounded-full text-sm font-medium border transition-colors
              {{ !$activeTag
                 ? 'bg-[var(--color-sawah-deep)] text-[var(--color-paper)] border-[var(--color-sawah-deep)]'
                 : 'border-[var(--color-bamboo-dark)] text-[var(--color-ink)]/70 hover:border-[var(--color-sawah-light)]' }}">
        Semua
    </a>

    @foreach ($tags as $tag)
        <a href="{{ $baseUrl }}?tag={{ $tag->slug }}"
           class="px-4 py-1.5 rounded-full text-sm font-medium border transition-colors
                  {{ $activeTag === $tag->slug
                     ? 'bg-[var(--color-sawah-deep)] text-[var(--color-paper)] border-[var(--color-sawah-deep)]'
                     : 'border-[var(--color-bamboo-dark)] text-[var(--color-ink)]/70 hover:border-[var(--color-sawah-light)]' }}">
            {{ $tag->nama }}
        </a>
    @endforeach
</div>
