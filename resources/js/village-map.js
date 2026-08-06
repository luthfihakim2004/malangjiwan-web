import L from 'leaflet';
import 'leaflet/dist/leaflet.css';

// Fix default marker icon paths breaking under Vite's asset pipeline.
import markerIcon2x from 'leaflet/dist/images/marker-icon-2x.png';
import markerIcon from 'leaflet/dist/images/marker-icon.png';
import markerShadow from 'leaflet/dist/images/marker-shadow.png';

delete L.Icon.Default.prototype._getIconUrl;
L.Icon.Default.mergeOptions({
    iconRetinaUrl: markerIcon2x,
    iconUrl: markerIcon,
    shadowUrl: markerShadow,
});

const COLORS = {
    wisata:               '#3D5A40', // sawah green
    umkm:                 '#C9602C', // bata terracotta
    route_main:           '#C9602C', // bata
    route_alt:            '#2B3A28', // dark
    place_kuliner:        '#B45309', // amber-700
    place_penginapan:     '#3B82F6', // blue-500
    place_fasilitas_umum: '#059669', // emerald-600
    place_usaha_lokal:    '#7C3AED',
};

function coloredIcon(type) {
    const color = COLORS[type] ?? '#2B3A28';

    if (type === 'route_main' || type === 'route_alt') {
        return L.divIcon({
            className: '',
            html: `<span style="
                display:block;width:14px;height:14px;border-radius:3px;
                background:${color};border:2px solid #FAF6EE;
                box-shadow:0 1px 3px rgba(0,0,0,.4);
                transform:rotate(45deg);
            "></span>`,
            iconSize: [14, 14],
            iconAnchor: [7, 7],
            popupAnchor: [0, -10],
        });
    }

    if (type && type.startsWith('place_')) {
        return L.divIcon({
            className: '',
            html: `<span style="
                display:block;width:12px;height:12px;border-radius:3px;
                background:${color};border:2px solid #FAF6EE;
                box-shadow:0 1px 3px rgba(0,0,0,.4);
            "></span>`,
            iconSize: [12, 12],
            iconAnchor: [6, 6],
            popupAnchor: [0, -8],
        });
    }

    return L.divIcon({
        className: '',
        html: `<span style="
            display:block;width:20px;height:20px;border-radius:50%;
            background:${color};border:3px solid #FAF6EE;
            box-shadow:0 1px 3px rgba(0,0,0,.4);
        "></span>`,
        iconSize: [14, 14],
        iconAnchor: [7, 7],
        popupAnchor: [0, -8],
    });
}

export function initVillageMap(el) {
    if (!el || el._leafletInitialized) return;
    el._leafletInitialized = true;

    let markers = [];
    try {
        markers = JSON.parse(el.dataset.markers || '[]');
    } catch (e) {
        console.error('Invalid markers JSON on map element', e);
    }

    const zoom = Number(el.dataset.zoom || 14);
    const center = markers.length
        ? [markers[0].lat, markers[0].lng]
        : [el.dataset.centerLat ? Number(el.dataset.centerLat) : -7.690555,
           el.dataset.centerLng ? Number(el.dataset.centerLng) : 110.5548639];

    const map = L.map(el, { scrollWheelZoom: false }).setView(center, zoom);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
        maxZoom: 19,
    }).addTo(map);

    const layerGroups = {
        wisata:               L.layerGroup(),
        umkm:                 L.layerGroup(),
        route_main:           L.layerGroup(),
        route_alt:            L.layerGroup(),
        place_kuliner:        L.layerGroup(),
        place_penginapan:     L.layerGroup(),
        place_fasilitas_umum: L.layerGroup(),
    };

    markers.forEach((m) => {
        if (typeof m.lat !== 'number' || typeof m.lng !== 'number') return;

        const marker = L.marker([m.lat, m.lng], { icon: coloredIcon(m.type) });

        const isPlace = m.type?.startsWith('place_');
        const isRoute = m.type === 'route_main' || m.type === 'route_alt';

        const typeLabel = {
            wisata:               'Wisata',
            umkm:                 'UMKM',
            route_main:           'Titik Masuk Utama',
            route_alt:            'Rute Alternatif',
            place_usaha_lokal:    'Usaha Lokal',
            place_kuliner:        'Kuliner',
            place_penginapan:     'Penginapan',
            place_fasilitas_umum: 'Fasilitas Umum',
        }[m.type] ?? m.type;

        let actionLink = '';
        if (isPlace && m.gmaps) {
            actionLink = `<a href="${m.gmaps}" target="_blank" rel="noopener noreferrer"
                style="font-size:13px;color:${COLORS[m.type]};text-decoration:underline;">
                Pergi ke Lokasi &rarr;</a>`;
        } else if (isRoute && m.url) {
            actionLink = `<a href="${m.url}" target="_blank" rel="noopener noreferrer"
                style="font-size:13px;color:#3D5A40;text-decoration:underline;">
                Buka di Google Maps &rarr;</a>`;
        } else if (m.url) {
            actionLink = `<a href="${m.url}"
                style="font-size:13px;color:#3D5A40;text-decoration:underline;">
                Info lengkap &rarr;</a>`;
        }

        marker.bindPopup(`
            <div style="font-family:var(--font-sans, sans-serif); min-width:160px;">
                <p style="font-size:11px;font-weight:600;letter-spacing:.08em;text-transform:uppercase;
                          color:${COLORS[m.type] ?? '#2B3A28'};margin:0 0 2px;">
                    ${typeLabel}${m.kategori && !isPlace ? ' · ' + m.kategori : ''}
                </p>
                <p style="font-weight:600;margin:0 0 6px;color:#232F21;">
                    ${m.nama ?? m.name ?? 'Lokasi'}
                </p>
                ${m.description ? `<p style="margin:0 0 6px;font-size:13px;color:#555;">${m.description}</p>` : ''}
                ${actionLink}
            </div>
        `);

        (layerGroups[m.type] ?? layerGroups.wisata).addLayer(marker);
    });

    Object.values(layerGroups).forEach(lg => lg.addTo(map));

    fetch('/maps/malangjiwan.geojson')
        .then(r => r.json())
        .then(data => {
            const boundary = L.geoJSON(data, {
                style: {
                    color: '#3D5A40',
                    weight: 2,
                    opacity: 0.8,
                    fillColor: '#3D5A40',
                    fillOpacity: 0.06,
                    dashArray: '6, 4',
                }
            }).addTo(map);

            if (markers.length === 0) {
                map.fitBounds(boundary.getBounds(), { padding: [24, 24] });
            }
        })
        .catch(() => {});

    if (markers.length > 1) {
        const bounds = L.latLngBounds(markers.map((m) => [m.lat, m.lng]));
        map.fitBounds(bounds, { padding: [32, 32] });
    }

    el._map = map;
    el._layerGroups = layerGroups;
}

export function initAllVillageMaps() {
    document.querySelectorAll('[data-village-map]').forEach(initVillageMap);
}

function destroyAllVillageMaps() {
    document.querySelectorAll('[data-village-map]').forEach(el => {
        if (el._map) {
            el._map.remove();
            el._map = null;
            el._layerGroups = null;
            el._leafletInitialized = false;
        }
    });
}

document.addEventListener('DOMContentLoaded', initAllVillageMaps);
document.addEventListener('livewire:navigated', initAllVillageMaps);

window.__villageMapInit = initAllVillageMaps;
