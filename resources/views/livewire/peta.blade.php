@section('title', 'Peta Desa Malangjiwan, Klaten')

<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-14">

    <div class="mb-8 flex flex-wrap items-end justify-between gap-4">
        <div>
            <p class="eyebrow mb-1">Sebaran Lokasi</p>
            <h1 class="font-display text-3xl sm:text-4xl font-semibold text-[var(--color-sawah-deep)]">
                Peta Desa Malangjiwan
            </h1>
            <p class="mt-2 text-[var(--color-ink)]/60">Semua lokasi wisata, UMKM, dan tempat menarik dalam satu peta.</p>
        </div>

        {{-- PDF download button --}}
        @if (file_exists(public_path('maps/peta-desa.pdf')))
            <a href="/maps/peta-desa.pdf"
               download="Peta-Desa-Malangjiwan.pdf"
               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border border-[var(--color-bamboo-dark)] text-sm font-medium text-[var(--color-ink)]/70 hover:border-[var(--color-sawah)] hover:text-[var(--color-sawah)] transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                </svg>
                Unduh Peta PDF
                <span class="text-xs text-[var(--color-ink)]/40">33 MB</span>
            </a>
        @endif
    </div>

    {{-- Tab switcher --}}
    <div x-data="{ tab: 'interaktif' }">

        <div class="flex gap-1 p-1 rounded-xl bg-[var(--color-bamboo)]/40 w-fit mb-6">
            <button @click="tab = 'interaktif'"
                    :class="tab === 'interaktif'
                        ? 'bg-[var(--color-paper)] text-[var(--color-sawah-deep)] shadow-sm'
                        : 'text-[var(--color-ink)]/50 hover:text-[var(--color-ink)]'"
                    class="px-4 py-2 rounded-lg text-sm font-medium transition-all">
                <span class="flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 6.75V15m6-6v8.25m.503 3.498l4.875-2.437c.381-.19.622-.58.622-1.006V4.82c0-.836-.88-1.38-1.628-1.006l-3.869 1.934c-.317.159-.69.159-1.006 0L9.503 3.252a1.125 1.125 0 00-1.006 0L3.622 5.689C3.24 5.88 3 6.27 3 6.695V19.18c0 .836.88 1.38 1.628 1.006l3.869-1.934c.317-.159.69-.159 1.006 0l4.994 2.497z" />
                    </svg>
                    Peta Interaktif
                </span>
            </button>
            <button @click="tab = 'statis'"
                    :class="tab === 'statis'
                        ? 'bg-[var(--color-paper)] text-[var(--color-sawah-deep)] shadow-sm'
                        : 'text-[var(--color-ink)]/50 hover:text-[var(--color-ink)]'"
                    class="px-4 py-2 rounded-lg text-sm font-medium transition-all">
                <span class="flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                    </svg>
                    Peta Statis (PDF)
                </span>
            </button>
        </div>

        {{-- ── Interactive map tab ── --}}
        <div x-show="tab === 'interaktif'" x-transition:enter="transition-opacity duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">

            {{-- Filters --}}
            <div class="flex flex-wrap items-center gap-x-5 gap-y-3 mb-6">

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

                <label class="flex items-center gap-2 cursor-pointer select-none">
                    <input type="checkbox" wire:model.live="showPlace"
                           class="rounded border-[var(--color-bamboo-dark)]">
                    <span class="text-sm font-medium flex items-center gap-1.5">
                        <span class="inline-block w-3 h-3 rounded-full bg-amber-600"></span>
                        Tempat Lainnya
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
                <x-map :markers="$markers" height="800px" :zoom="14" />
            </div>

            @if (empty($markers))
                <p class="mt-4 text-sm text-center text-[var(--color-ink)]/50">
                    Tidak ada lokasi yang cocok dengan filter ini.
                </p>
            @endif

            {{-- Legend --}}
            <div class="mt-6 flex flex-wrap gap-x-6 gap-y-2 text-xs text-[var(--color-ink)]/60">
                <span class="flex items-center gap-1.5">
                    <span class="inline-block w-3 h-3 rounded-full bg-[var(--color-sawah)] border-2 border-[var(--color-paper)]"></span>
                    Destinasi Wisata
                </span>
                <span class="flex items-center gap-1.5">
                    <span class="inline-block w-3 h-3 rounded-full bg-[var(--color-bata)] border-2 border-[var(--color-paper)]"></span>
                    UMKM Terdaftar
                </span>
                <span class="flex items-center gap-1.5">
                    <span class="inline-block w-2.5 h-2.5 rounded-sm bg-amber-700 border-2 border-[var(--color-paper)]"></span>
                    Kuliner
                </span>
                <span class="flex items-center gap-1.5">
                    <span class="inline-block w-2.5 h-2.5 rounded-sm bg-blue-500 border-2 border-[var(--color-paper)]"></span>
                    Penginapan
                </span>
                <span class="flex items-center gap-1.5">
                    <span class="inline-block w-2.5 h-2.5 rounded-sm bg-emerald-600 border-2 border-[var(--color-paper)]"></span>
                    Fasilitas Umum
                </span>
                <span class="flex items-center gap-1.5">
                    <span class="inline-block w-2.5 h-2.5 rounded-sm bg-violet-600 border-2 border-[var(--color-paper)]"></span>
                    Usaha Lokal
                </span>
                <span class="ml-auto">Data: OpenStreetMap</span>
            </div>
        </div>

        {{-- ── Static PDF tab ── --}}
        <div x-show="tab === 'statis'" x-transition:enter="transition-opacity duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
            @if (file_exists(public_path('maps/peta-desa.pdf')))
                <div class="rounded-2xl overflow-hidden border border-[var(--color-bamboo)] bg-[var(--color-ink)]">
                    {{-- Native browser PDF viewer --}}
                    <iframe
                        src="/maps/peta-desa.pdf"
                        class="w-full"
                        style="height: 1000px;"
                        title="Peta Desa Malangjiwan"
                        loading="lazy"
                    ></iframe>
                </div>
                <div class="mt-4 flex items-center justify-between text-xs text-[var(--color-ink)]/50">
                    <span>Peta administratif Desa Malangjiwan</span>
                    <a href="/maps/peta-desa.pdf"
                       download="Peta-Desa-Malangjiwan.pdf"
                       class="flex items-center gap-1.5 text-[var(--color-sawah)] hover:text-[var(--color-bata)] transition-colors font-medium">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                        </svg>
                        Unduh PDF (33 MB)
                    </a>
                </div>
            @else
                <div class="rounded-2xl border border-[var(--color-bamboo)] bg-[var(--color-paper-dim)] p-16 text-center">
                    <p class="text-[var(--color-ink)]/40 text-sm">Peta statis belum tersedia.</p>
                    <p class="text-xs text-[var(--color-ink)]/30 mt-1">Letakkan file PDF di <code>public/maps/peta-desa.pdf</code></p>
                </div>
            @endif
        </div>

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
