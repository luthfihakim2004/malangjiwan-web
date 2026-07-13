@props([
    'markers' => [],   // array of ['lat' => float, 'lng' => float, 'nama' => string, 'type' => 'umkm'|'wisata', 'kategori' => string|null, 'url' => string|null]
    'height' => '400px',
    'zoom' => 14,
])

@php
    // Defensive numeric casting — model casts return decimal strings,
    // and JSON-encoding a string lat/lng breaks Leaflet's L.marker([...]).
    $normalized = collect($markers)
        ->map(function ($m) {
            $m = (array) $m;
            $m['lat'] = isset($m['lat']) ? (float) $m['lat'] : null;
            $m['lng'] = isset($m['lng']) ? (float) $m['lng'] : null;
            return $m;
        })
        ->filter(fn ($m) => $m['lat'] !== null && $m['lng'] !== null)
        ->values();
@endphp

<div
    {{ $attributes->class(['village-map rounded-xl overflow-hidden border border-[var(--color-bamboo)]']) }}
    style="height: {{ $height }};"
    data-village-map
    data-markers="{{ $normalized->toJson() }}"
    data-zoom="{{ $zoom }}"
    role="application"
    aria-label="Peta lokasi"
></div>

@if ($normalized->isEmpty())
    <p class="text-sm text-[var(--color-ink)]/60 mt-2">Lokasi belum tersedia di peta.</p>
@endif
