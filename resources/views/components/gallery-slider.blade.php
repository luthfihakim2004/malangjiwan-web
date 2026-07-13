@props([
    'media'    => collect(),
    'fallback' => null,
    'aspect'   => 'aspect-video',
    'alt'      => '',
])

@php
    $slides = $media->isNotEmpty()
        ? $media->map(fn ($m) => [
            'src'     => asset('storage/' . $m->path),
            'caption' => $m->caption,
          ])->toArray()
        : ($fallback
            ? [['src' => asset('storage/' . $fallback), 'caption' => null]]
            : []);
@endphp

@if (count($slides) === 0)
    <div class="{{ $aspect }} rounded-2xl overflow-hidden bg-[var(--color-bamboo)] flex items-center justify-center">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-[var(--color-bamboo-dark)]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909M3.75 18h16.5M3 6.75h18M3 12h18" />
        </svg>
    </div>
@else
    <div
        x-data="{
            current: 0,
            total: {{ count($slides) }},
            timer: null,
            lightbox: false,
            slides: {{ Js::from($slides) }},

            init() { this.startAutoplay(); },
            startAutoplay() {
                if (this.total <= 1) return;
                this.timer = setInterval(() => this.next(), 4000);
            },
            stopAutoplay() { clearInterval(this.timer); },
            prev() {
                this.stopAutoplay();
                this.current = (this.current - 1 + this.total) % this.total;
                this.startAutoplay();
            },
            next() {
                this.current = (this.current + 1) % this.total;
            },
            goTo(i) {
                this.stopAutoplay();
                this.current = i;
                this.startAutoplay();
            },
            openLightbox() {
                this.lightbox = true;
                this.stopAutoplay();
                document.body.style.overflow = 'hidden';
                document.querySelectorAll('[data-village-map]').forEach(el => {
                    el.style.visibility = 'hidden';
                });
            },
            closeLightbox() {
                this.lightbox = false;
                document.body.style.overflow = '';
                this.startAutoplay();
                document.querySelectorAll('[data-village-map]').forEach(el => {
                    el.style.visibility = 'visible';
                });
            },
        }"
        @keydown.escape.window="closeLightbox()"
        @keydown.arrow-left.window="lightbox && prev()"
        @keydown.arrow-right.window="lightbox && next()"
        @mouseenter="stopAutoplay()"
        @mouseleave="!lightbox && startAutoplay()"
    >
        {{-- ── Inline slider ── --}}
        <div class="relative {{ $aspect }} rounded-2xl overflow-hidden bg-[var(--color-ink)] group cursor-zoom-in"
             @click="openLightbox()">

            <template x-for="(slide, index) in slides" :key="index">
                <div
                    x-show="current === index"
                    x-transition:enter="transition-opacity duration-500"
                    x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100"
                    x-transition:leave="transition-opacity duration-300"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    class="absolute inset-0 flex items-center justify-center"
                >
                    <img
                        :src="slide.src"
                        :alt="slide.caption || '{{ $alt }}'"
                        class="max-w-full max-h-full w-full h-full object-contain"
                    >

                    <template x-if="slide.caption">
                        <div class="absolute bottom-0 inset-x-0 bg-gradient-to-t from-black/60 to-transparent px-5 py-4">
                            <p class="text-white text-sm" x-text="slide.caption"></p>
                        </div>
                    </template>
                </div>
            </template>

            @if (count($slides) > 1)
                <button @click.stop="prev()"
                    class="absolute left-3 top-1/2 -translate-y-1/2 w-9 h-9 rounded-full bg-black/30 hover:bg-black/50 text-white flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity z-10"
                    aria-label="Foto sebelumnya">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                    </svg>
                </button>
                <button @click.stop="next()"
                    class="absolute right-3 top-1/2 -translate-y-1/2 w-9 h-9 rounded-full bg-black/30 hover:bg-black/50 text-white flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity z-10"
                    aria-label="Foto berikutnya">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                    </svg>
                </button>

                <div class="absolute bottom-3 inset-x-0 flex justify-center gap-1.5 z-10" @click.stop>
                    <template x-for="(slide, index) in slides" :key="index">
                        <button
                            @click="goTo(index)"
                            :class="current === index ? 'w-5 bg-white' : 'w-2 bg-white/50 hover:bg-white/75'"
                            class="h-2 rounded-full transition-all duration-300"
                            :aria-label="`Foto ${index + 1}`"
                        ></button>
                    </template>
                </div>

                <div class="absolute top-3 right-3 bg-black/30 text-white text-xs px-2 py-1 rounded-full z-10 tabular-nums" @click.stop>
                    <span x-text="current + 1"></span>/<span x-text="total"></span>
                </div>
            @endif

            {{-- Click hint --}}
            <div class="absolute top-3 left-3 bg-black/30 text-white text-xs px-2 py-1 rounded-full z-10 opacity-0 group-hover:opacity-100 transition-opacity select-none">
                Klik untuk perbesar
            </div>
        </div>

        {{-- ── Lightbox ── --}}
        <div
            x-show="lightbox"
            x-transition:enter="transition-opacity duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition-opacity duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-[1000] bg-black/92 flex items-center justify-center"
            @click.self="closeLightbox()"
            role="dialog"
            aria-modal="true"
            aria-label="Galeri foto"
            style="display: none;"
        >
            {{-- Close button --}}
            <button @click="closeLightbox()"
                class="absolute top-4 right-4 w-10 h-10 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition-colors z-10"
                aria-label="Tutup">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            {{-- Counter --}}
            <div class="absolute top-4 left-1/2 -translate-x-1/2 bg-white/10 text-white text-sm px-3 py-1.5 rounded-full tabular-nums">
                <span x-text="current + 1"></span> / <span x-text="total"></span>
            </div>

            {{-- Image area --}}
            <div class="relative w-full h-full flex items-center justify-center px-16 py-16">
                <template x-for="(slide, index) in slides" :key="index">
                    <div
                        x-show="current === index"
                        x-transition:enter="transition-opacity duration-300"
                        x-transition:enter-start="opacity-0"
                        x-transition:enter-end="opacity-100"
                        x-transition:leave="transition-opacity duration-200"
                        x-transition:leave-start="opacity-100"
                        x-transition:leave-end="opacity-0"
                        class="absolute inset-0 flex flex-col items-center justify-center px-16 py-16 gap-4"
                    >
                        <img
                            :src="slide.src"
                            :alt="slide.caption || '{{ $alt }}'"
                            class="max-w-full max-h-[80vh] object-contain rounded-lg"
                        >
                        <template x-if="slide.caption">
                            <p class="text-white/80 text-sm text-center max-w-lg" x-text="slide.caption"></p>
                        </template>
                    </div>
                </template>
            </div>

            {{-- Lightbox prev/next --}}
            @if (count($slides) > 1)
                <button @click="prev()"
                    class="absolute left-4 top-1/2 -translate-y-1/2 w-12 h-12 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition-colors"
                    aria-label="Foto sebelumnya">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                    </svg>
                </button>
                <button @click="next()"
                    class="absolute right-4 top-1/2 -translate-y-1/2 w-12 h-12 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition-colors"
                    aria-label="Foto berikutnya">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                    </svg>
                </button>

                {{-- Lightbox dots --}}
                <div class="absolute bottom-6 inset-x-0 flex justify-center gap-2">
                    <template x-for="(slide, index) in slides" :key="index">
                        <button
                            @click="goTo(index)"
                            :class="current === index ? 'w-6 bg-white' : 'w-2 bg-white/30 hover:bg-white/60'"
                            class="h-2 rounded-full transition-all duration-300"
                            :aria-label="`Foto ${index + 1}`"
                        ></button>
                    </template>
                </div>
            @endif
        </div>
    </div>
@endif
