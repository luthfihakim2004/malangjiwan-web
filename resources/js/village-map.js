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
    wisata: '#3D5A40', // sawah green
    umkm: '#C9602C',   // bata terracotta
};

function coloredIcon(type) {
    const color = COLORS[type] ?? '#2B3A28';
    return L.divIcon({
        className: '',
        html: `<span style="
            display:block;width:14px;height:14px;border-radius:50%;
            background:${color};border:2px solid #FAF6EE;
            box-shadow:0 1px 3px rgba(0,0,0,.4);
        "></span>`,
        iconSize: [14, 14],
        iconAnchor: [7, 7],
        popupAnchor: [0, -8],
    });
}

/**
 * Initializes a Leaflet map inside the given element.
 *
 * @param {HTMLElement} el - container element with the markers JSON in data-markers
 */
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

    const layerGroups = { wisata: L.layerGroup(), umkm: L.layerGroup() };

    markers.forEach((m) => {
        if (typeof m.lat !== 'number' || typeof m.lng !== 'number') return;

        const marker = L.marker([m.lat, m.lng], { icon: coloredIcon(m.type) });

        const popupHtml = `
            <div style="font-family:var(--font-sans, sans-serif); min-width:160px;">
                <p style="font-size:11px;font-weight:600;letter-spacing:.08em;text-transform:uppercase;color:${COLORS[m.type] ?? '#2B3A28'};margin:0 0 2px;">
                    ${m.type === 'umkm' ? 'UMKM' : 'Wisata'}${m.kategori ? ' · ' + m.kategori : ''}
                </p>
                <p style="font-weight:600;margin:0 0 4px;color:#232F21;">${m.nama}</p>
                ${m.url ? `<a href="${m.url}" style="font-size:13px;color:#3D5A40;text-decoration:underline;">Lihat detail &rarr;</a>` : ''}
            </div>
        `;
        marker.bindPopup(popupHtml);

        (layerGroups[m.type] ?? layerGroups.wisata).addLayer(marker);
    });

    layerGroups.wisata.addTo(map);
    layerGroups.umkm.addTo(map);

    // Draw village boundary
    fetch('/maps/malangjiwan.geojson')
        .then(r => r.json())
        .then(data => {
            const boundary = L.geoJSON(data, {
                style: {
                    color: '#3D5A40',       // sawah green — matches your brand
                    weight: 2,
                    opacity: 0.8,
                    fillColor: '#3D5A40',
                    fillOpacity: 0.06,     // very subtle fill, just a tint
                    dashArray: '6, 4',     // dashed border — feels like a boundary, not a solid shape
                }
            }).addTo(map);

            // On the combined /peta map (multiple markers), fit bounds to the
            // boundary polygon instead of just the marker cluster — shows the
            // full village extent even if pins only cover part of it.
            if (markers.length === 0) {
                map.fitBounds(boundary.getBounds(), { padding: [24, 24] });
            }
        })
        .catch(() => {
            // Silently fail — boundary is decorative, not critical
        });

    // Fit bounds if there are multiple markers (combined map use case)
    if (markers.length > 1) {
        const bounds = L.latLngBounds(markers.map((m) => [m.lat, m.lng]));
        map.fitBounds(bounds, { padding: [32, 32] });
    }

    // Expose layer groups + map instance on the element so a Livewire
    // component or Alpine wrapper can wire up type-toggle checkboxes.
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
