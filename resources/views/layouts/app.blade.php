<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">

    <link rel="icon"
          type="image/png"
          sizes="32x32"
          href="{{ asset('favicon-32x32.png') }}">

    <link rel="icon"
          type="image/png"
          sizes="16x16"
          href="{{ asset('favicon-16x16.png') }}">

    <link rel="apple-touch-icon"
          sizes="180x180"
          href="{{ asset('apple-touch-icon.png') }}">

    <link rel="manifest" href="{{ asset('site.webmanifest') }}">

    @php
        $siteName = 'Desa Malangjiwan';

        $pageTitle = trim($__env->yieldContent('title'))
            ?: 'Desa Malangjiwan - Wisata, UMKM, dan Informasi Desa';

        $pageDescription = trim($__env->yieldContent('meta_description'))
            ?: 'Portal resmi informasi Desa Malangjiwan.';

        $canonicalUrl = trim($__env->yieldContent('canonical'))
            ?: url()->current();

        $ogImage = trim($__env->yieldContent('og_image'))
            ?: asset('images/og-default.jpg');

        $ogType = trim($__env->yieldContent('og_type'))
            ?: 'website';
    @endphp

    <title>{{ $pageTitle }}</title>

    <meta name="description" content="{{ $pageDescription }}">
    <meta name="robots" content="@yield('robots', 'index, follow')">

    <link rel="canonical" href="{{ $canonicalUrl }}">

    <meta property="og:site_name" content="{{ $siteName }}">
    <meta property="og:type" content="{{ $ogType }}">
    <meta property="og:title" content="{{ $pageTitle }}">
    <meta property="og:description" content="{{ $pageDescription }}">
    <meta property="og:url" content="{{ $canonicalUrl }}">
    <meta property="og:image" content="{{ $ogImage }}">
    <meta property="og:locale" content="id_ID">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $pageTitle }}">
    <meta name="twitter:description" content="{{ $pageDescription }}">
    <meta name="twitter:image" content="{{ $ogImage }}">

    @stack('structured-data')

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="font-sans antialiased">
    {{-- Skip link for keyboard users --}}
    <a href="#main-content"
       class="sr-only focus:not-sr-only focus:absolute focus:top-2 focus:left-2 focus:z-50 focus:bg-[var(--color-sawah-deep)] focus:text-[var(--color-paper)] focus:px-4 focus:py-2 focus:rounded-md">
        Lompat ke konten utama
    </a>

    <header class="border-b border-[var(--color-bamboo)] bg-[var(--color-paper)]/95 backdrop-blur sticky top-0 z-40"
            x-data="{ mobileOpen: false }">

        <nav class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between" role="navigation">

            <!-- Logo -->
            <a href="{{ route('home') }}"
               class="flex items-center gap-2 group"
               aria-label="Beranda - Desa Malangjiwan">
                <span class="font-display text-lg font-semibold text-[var(--color-sawah-deep)] group-hover:text-[var(--color-bata)] transition-colors">
                    Malangjiwan
                </span>
            </a>

            <!-- Desktop Menu -->
            <ul class="hidden md:flex items-stretch h-full text-sm font-medium text-[var(--color-ink)]">
                <li><a href="{{ route('home') }}"
                   class="flex items-center h-full px-4 transition-colors
                        hover:bg-[var(--color-bamboo-dark)]
                        hover:text-[var(--color-sawah-deep)]
                        focus:outline-none
                        {{ request()->routeIs('home')
                            ? 'text-[var(--color-bata)]'
                            : '' }}"
                   @if(request()->routeIs('home')) aria-current="page" @endif>
                    Beranda
                </a></li>
                <li><a href="{{ route('post.index') }}"
                   class="flex items-center h-full px-4 transition-colors
                        hover:bg-[var(--color-bamboo-dark)]
                        hover:text-[var(--color-sawah-deep)]
                        focus:outline-none
                        {{ request()->routeIs('post.*')
                            ? 'text-[var(--color-bata)]'
                            : '' }}"
                   @if(request()->routeIs('post.*')) aria-current="page" @endif>
                    Berita
                </a></li>
                <li><a href="{{ route('wisata.index') }}"
                   class="flex items-center h-full px-4 transition-colors
                        hover:bg-[var(--color-bamboo-dark)]
                        hover:text-[var(--color-sawah-deep)]
                        focus:outline-none
                        {{ request()->routeIs('wisata.*')
                            ? 'text-[var(--color-bata)]'
                            : '' }}"
                   @if(request()->routeIs('wisata.*')) aria-current="page" @endif>
                    Wisata
                </a></li>
                <li><a href="{{ route('umkm.index') }}"
                   class="flex items-center h-full px-4 transition-colors
                        hover:bg-[var(--color-bamboo-dark)]
                        hover:text-[var(--color-sawah-deep)]
                        focus:outline-none
                        {{ request()->routeIs('umkm.*')
                            ? 'text-[var(--color-bata)]'
                            : '' }}"
                   @if(request()->routeIs('umkm.*')) aria-current="page" @endif>
                    UMKM
                </a></li>
                <li><a href="{{ route('vegetasi.index') }}"
                   class="flex items-center h-full px-4 transition-colors
                        hover:bg-[var(--color-bamboo-dark)]
                        hover:text-[var(--color-sawah-deep)]
                        focus:outline-none
                        {{ request()->routeIs('vegetasi.*')
                            ? 'text-[var(--color-bata)]'
                            : '' }}"
                   @if(request()->routeIs('vegetasi.*')) aria-current="page" @endif>
                    Vegetasi
                </a></li>
                <li><a href="{{ route('galeri.index') }}"
                   class="flex items-center h-full px-4 transition-colors
                        hover:bg-[var(--color-bamboo-dark)]
                        hover:text-[var(--color-sawah-deep)]
                        focus:outline-none
                        {{ request()->routeIs('galeri.*')
                            ? 'text-[var(--color-bata)]'
                            : '' }}"
                   @if(request()->routeIs('galeri.*')) aria-current="page" @endif>
                    Galeri
                </a></li>
                <li><a href="{{ route('peta') }}"
                   class="flex items-center h-full px-4 transition-colors
                        hover:bg-[var(--color-bamboo-dark)]
                        hover:text-[var(--color-sawah-deep)]
                        focus:outline-none
                        {{ request()->routeIs('peta')
                            ? 'text-[var(--color-bata)]'
                            : '' }}"
                   @if(request()->routeIs('peta')) aria-current="page" @endif>
                    Peta
                </a></li>
                <li><a href="{{ route('profil') }}"
                   class="flex items-center h-full px-4 transition-colors
                        hover:bg-[var(--color-bamboo-dark)]
                        hover:text-[var(--color-sawah-deep)]
                        focus:outline-none
                        {{ request()->routeIs('profil')
                            ? 'text-[var(--color-bata)]'
                            : '' }}"
                   @if(request()->routeIs('profil')) aria-current="page" @endif>
                    Profil Desa
                </a></li>
            </ul>

            <!-- Mobile Menu Button -->
            <button
                @click="mobileOpen = !mobileOpen"
                class="md:hidden p-2 -mr-2 text-[var(--color-sawah-deep)]"
                aria-label="Toggle navigation menu"
                :aria-expanded="mobileOpen"
                aria-controls="mobile-menu">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          :d="mobileOpen ? 'M6 18L18 6M6 6h12v12' : 'M4 6h16M4 12h16M4 18h16'" />
                </svg>
            </button>
        </nav>

        <!-- Mobile Menu -->
        <div id="mobile-menu"
             x-show="mobileOpen"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-4"
             x-transition:enter-end="opacity-100 translate-y-0"
             class="md:hidden fixed left-0 right-0 top-16 bg-[var(--color-paper)] border-b border-[var(--color-bamboo)] shadow-xl z-50 overflow-y-auto"
             style="max-height: calc(100vh - 4rem);">

            <div class="px-4 py-6 space-y-1">
                <a href="{{ route('home') }}"
                   @click="mobileOpen = false"
                   class="block py-4 px-4 text-base font-medium hover:bg-[var(--color-bamboo-dark)] rounded-xl transition-colors">
                    Beranda
                </a>
                <a href="{{ route('post.index') }}"
                   @click="mobileOpen = false"
                   class="block py-4 px-4 text-base font-medium hover:bg-[var(--color-bamboo-dark)] rounded-xl transition-colors">
                    Berita
                </a>
                <a href="{{ route('wisata.index') }}"
                   @click="mobileOpen = false"
                   class="block py-4 px-4 text-base font-medium hover:bg-[var(--color-bamboo-dark)] rounded-xl transition-colors">
                    Wisata
                </a>
                <a href="{{ route('umkm.index') }}"
                   @click="mobileOpen = false"
                   class="block py-4 px-4 text-base font-medium hover:bg-[var(--color-bamboo-dark)] rounded-xl transition-colors">
                    UMKM
                </a>
                <a href="{{ route('vegetasi.index') }}"
                   @click="mobileOpen = false"
                   class="block py-4 px-4 text-base font-medium hover:bg-[var(--color-bamboo-dark)] rounded-xl transition-colors">
                    Vegetasi
                </a>
                <a href="{{ route('galeri.index') }}"
                   @click="mobileOpen = false"
                   class="block py-4 px-4 text-base font-medium hover:bg-[var(--color-bamboo-dark)] rounded-xl transition-colors">
                    Galeri
                </a>
                <a href="{{ route('peta') }}"
                   @click="mobileOpen = false"
                   class="block py-4 px-4 text-base font-medium hover:bg-[var(--color-bamboo-dark)] rounded-xl transition-colors">
                    Peta
                </a>
                <a href="{{ route('profil') }}"
                   @click="mobileOpen = false"
                   class="block py-4 px-4 text-base font-medium hover:bg-[var(--color-bamboo-dark)] rounded-xl transition-colors">
                    Profil Desa
                </a>
            </div>
        </div>
    </header>

    <main id="main-content">
        @hasSection('content')
            @yield('content')
        @else
            {{ $slot ?? '' }}
        @endif
    </main>

    <footer class="mt-24 border-t border-[var(--color-bamboo)] bg-[var(--color-paper-dim)]">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12 grid grid-cols-1 sm:grid-cols-3 gap-8">

            <!-- Brand -->
            <div>
                <p class="font-display text-lg font-semibold text-[var(--color-sawah-deep)]">Desa Malangjiwan</p>
                <p class="mt-2 text-sm text-[var(--color-ink)]/70">Portal resmi informasi Desa Malangjiwan.</p>
            </div>

            <!-- Quick Links -->
            <div>
                <p class="eyebrow mb-3">Tautan Cepat</p>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('post.index') }}" class="hover:text-[var(--color-bata)]">Berita Desa</a></li>
                    <li><a href="{{ route('umkm.index') }}" class="hover:text-[var(--color-bata)]">Daftar UMKM</a></li>
                    <li><a href="{{ route('wisata.index') }}" class="hover:text-[var(--color-bata)]">Destinasi Wisata</a></li>
                    <li><a href="{{ route('aspirasi.index') }}" class="hover:text-[var(--color-bata)]">Lapor & Aspirasi</a></li>
                </ul>
            </div>

            {{-- Dynamic Contacts --}}
            <div>
                <p class="eyebrow mb-3">Hubungi Kami</p>

                @php
                    $footerProfile = \App\Models\Profile::with('contacts')->first();
                @endphp

                @if ($footerProfile?->alamat_kantor)
                    <p class="text-sm text-[var(--color-ink)]/70 mb-3">{{ $footerProfile->alamat_kantor }}</p>
                @endif

                @if ($footerProfile?->contacts->isNotEmpty())
                    <x-contact-list :contacts="$footerProfile->contacts" />
                @else
                    <p class="text-sm text-[var(--color-ink)]/60 italic">Kontak belum diatur.</p>
                @endif
            </div>

        </div>

        <div class="border-t border-[var(--color-bamboo)] py-4 text-center text-xs text-[var(--color-ink)]/50">
            &copy; {{ date('Y') }} Pemerintah Desa Malangjiwan
        </div>
    </footer>

    @livewireScripts
    @stack('scripts')
</body>
</html>
