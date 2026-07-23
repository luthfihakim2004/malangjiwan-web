@section('title', 'Peta Desa Malangjiwan, Klaten')

<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
    <div class="mb-8">
        <p class="eyebrow mb-1">Sebaran Lokasi</p>
        <h1 class="font-display text-3xl sm:text-4xl font-semibold text-[var(--color-sawah-deep)]">
            Peta Desa Malangjiwan
        </h1>
        <p class="mt-2 text-[var(--color-ink)]/60">Semua lokasi wisata dan UMKM dalam satu peta.</p>
    </div>

    <div class="flex flex-wrap items-center gap-4 mb-6">

        <label class="flex items-center gap-2 cursor-pointer select-none">
            <input type="checkbox" wire:model.live="showWisata"
                   class="rounded border-[var(--color-bamboo-dark)] text-[var(--color-sawah)] focus:ring-[var(--color-sawah)]">
            <span class="text-sm font-medium flex items-center gap-1.5">
                <span class="inline-block w-3 h-3 rounded-full bg-[var(--color-sawah)]"></span>
                Wisata
            </span>
        </label>

        <label class="flex items-center gap-2 cursor-pointer select-none">
            <input type="checkbox" wire:model.live="showUmkm"
                   class="rounded border-[var(--color-bamboo-dark)] text-[var(--color-bata)] focus:ring-[var(--color-bata)]">
            <span class="text-sm font-medium flex items-center gap-1.5">
                <span class="inline-block w-3 h-3 rounded-full bg-[var(--color-bata)]"></span>
                UMKM
            </span>
        </label>

        <div class="hidden sm:block h-5 w-px bg-[var(--color-bamboo-dark)]"></div>

        @if ($tags->isNotEmpty())
            <select wire:model.live="tag"
                    class="text-sm border border-[var(--color-bamboo-dark)] rounded-full px-4 py-1.5 bg-[var(--color-paper)] text-[var(--color-ink)] focus:outline-none focus:ring-2 focus:ring-[var(--color-sawah)] focus:border-transparent">
                <option value="">Semua Tag</option>
                @foreach ($tags as $t)
                    <option value="{{ $t->slug }}">{{ $t->nama }}</option>
                @endforeach
            </select>
        @endif

        <span class="ml-auto text-xs text-[var(--color-ink)]/50 tabular-nums">
            {{ count($markers) }} lokasi
        </span>
    </div>

    <div id="peta-map-container">
        <x-map
            :markers="$markers"
            height="560px"
            :zoom="13"
        />
    </div>

    @if (empty($markers))
        <p class="mt-4 text-sm text-center text-[var(--color-ink)]/50">
            Tidak ada lokasi yang cocok dengan filter ini.
        </p>
    @endif

    <div class="mt-6 flex flex-wrap gap-6 text-xs text-[var(--color-ink)]/60">
        <span class="flex items-center gap-1.5">
            <span class="inline-block w-3 h-3 rounded-full bg-[var(--color-sawah)] border-2 border-[var(--color-paper)]"></span>
            Destinasi Wisata
        </span>
        <span class="flex items-center gap-1.5">
            <span class="inline-block w-3 h-3 rounded-full bg-[var(--color-bata)] border-2 border-[var(--color-paper)]"></span>
            UMKM Lokal
        </span>
        <span class="ml-auto">Data: OpenStreetMap</span>
    </div>
</div>
<script>
    window.addEventListener('peta-updated', () => {
        document.querySelectorAll('[data-village-map]').forEach(el => {
            if (el._map) { el._map.remove(); el._map = null; el._leafletInitialized = false; }
        });
        if (window.__villageMapInit) window.__villageMapInit();
    });
</script>
