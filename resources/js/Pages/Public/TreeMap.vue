<script setup>
import PublicLayout from '@/Layouts/PublicLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import L from 'leaflet';
import 'leaflet/dist/leaflet.css';
import { computed, nextTick, onMounted, onUnmounted, ref, watch } from 'vue';

const props = defineProps({
    trees: {
        type: Array,
        default: () => [],
    },
});

const searchQuery = ref('');
const statusFilter = ref('all');
const selectedTree = ref(props.trees[0] || null);
const mapMode = ref('streets'); // 'streets' or 'satellite'
const mapContainer = ref(null);

let map = null;
let tileLayer = null;
const markersMap = new Map();

// Tile URLs (Free, fast, reliable Open Source Tiles - Zero API Key Needed)
const TILE_STREETS = 'https://tile.openstreetmap.org/{z}/{x}/{y}.png';
const TILE_SATELLITE = 'https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}';

const filteredTrees = computed(() => {
    return props.trees.filter((tree) => {
        const matchesQuery =
            tree.common_name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
            tree.species_name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
            tree.tree_code.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
            (tree.location?.address_description || '').toLowerCase().includes(searchQuery.value.toLowerCase());

        const matchesStatus =
            statusFilter.value === 'all' ||
            (statusFilter.value === 'adopted' && tree.status === 'adopted') ||
            (statusFilter.value === 'available' && tree.status !== 'adopted');

        return matchesQuery && matchesStatus;
    });
});

function createCustomIcon(tree, isSelected = false) {
    const isAdopted = tree.status === 'adopted';
    const bgClass = isSelected
        ? 'bg-[#15803d] text-white ring-4 ring-[#86efac] scale-110 shadow-xl'
        : isAdopted
            ? 'bg-amber-600 text-white shadow-md'
            : 'bg-[#166534] text-white shadow-md hover:scale-105';

    const html = `
        <div class="transition-all duration-200 cursor-pointer flex flex-col items-center">
            <div class="px-2.5 py-1 rounded-xl text-[10px] font-black flex items-center gap-1.5 border border-white/80 ${bgClass}">
                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M17 8C8 10 5.9 16.17 3.82 21.34l1.89.66.95-2.3c.48.17.98.3 1.34.3C19 20 22 3 22 3c-1 2-8 2.25-13 3.75C7 7.25 5 9 5 11c0 2 2 3 2 3s2.5-3 10-6z" />
                </svg>
                <span class="font-mono">${tree.tree_code}</span>
            </div>
            <div class="w-2 h-2 rotate-45 -mt-1 ${isSelected ? 'bg-[#15803d]' : isAdopted ? 'bg-amber-600' : 'bg-[#166534]'} border-r border-b border-white"></div>
        </div>
    `;

    return L.divIcon({
        className: 'custom-leaflet-tree-marker',
        html,
        iconSize: [85, 36],
        iconAnchor: [42, 34],
        popupAnchor: [0, -32],
    });
}

function initMap() {
    if (!mapContainer.value) return;

    // Default center in Jakarta
    const defaultCenter = [-6.2146, 106.8451];

    map = L.map(mapContainer.value, {
        center: defaultCenter,
        zoom: 12,
        zoomControl: false,
    });

    // Custom Zoom controls in top right
    L.control.zoom({ position: 'topright' }).addTo(map);

    tileLayer = L.tileLayer(TILE_STREETS, {
        maxZoom: 19,
        attribution: '&copy; OpenStreetMap contributors &copy; CARTO',
    }).addTo(map);

    renderMarkers();
}

function switchTileMode(mode) {
    mapMode.value = mode;
    if (!map || !tileLayer) return;

    map.removeLayer(tileLayer);
    const url = mode === 'satellite' ? TILE_SATELLITE : TILE_STREETS;
    const attribution = mode === 'satellite'
        ? '&copy; Esri World Imagery'
        : '&copy; OpenStreetMap &copy; CARTO';

    tileLayer = L.tileLayer(url, { maxZoom: 19, attribution }).addTo(map);
}

function renderMarkers() {
    if (!map) return;

    // Clear old markers
    markersMap.forEach((marker) => marker.remove());
    markersMap.clear();

    const bounds = L.latLngBounds([]);

    filteredTrees.value.forEach((tree) => {
        const lat = parseFloat(tree.location?.public_latitude || tree.location?.latitude || -6.2088);
        const lng = parseFloat(tree.location?.public_longitude || tree.location?.longitude || 106.8456);

        if (isNaN(lat) || isNaN(lng)) return;

        const isSelected = selectedTree.value?.id === tree.id;
        const icon = createCustomIcon(tree, isSelected);

        const marker = L.marker([lat, lng], { icon }).addTo(map);

        // Interactive Popup
        const popupContent = document.createElement('div');
        popupContent.className = 'p-1 text-slate-800 space-y-2';
        popupContent.innerHTML = `
            <div class="border-b border-slate-100 pb-1.5">
                <span class="text-[9px] font-mono font-black px-1.5 py-0.5 rounded ${tree.status === 'adopted' ? 'bg-amber-100 text-amber-800' : 'bg-emerald-100 text-emerald-800'}">
                    ${tree.tree_code} • ${tree.status === 'adopted' ? 'Diadopsi' : 'Tersedia'}
                </span>
                <h4 class="font-extrabold text-sm text-slate-900 mt-1">${tree.common_name}</h4>
                <p class="text-[10px] text-slate-500 italic">${tree.species_name}</p>
            </div>
            <div class="text-[11px] text-slate-600 leading-tight">
                <strong>Serapan:</strong> ${tree.co2_absorption_kg_year} kg CO₂/th<br/>
                <strong>Area:</strong> ${tree.location?.address_description || 'Area Sekolah'}
            </div>
        `;

        marker.bindPopup(popupContent, { maxWidth: 240 });

        marker.on('click', () => {
            selectTree(tree, false);
        });

        markersMap.set(tree.id, marker);
        bounds.extend([lat, lng]);
    });

    if (bounds.isValid()) {
        map.fitBounds(bounds, { padding: [50, 50], maxZoom: 15 });
    }
}

function selectTree(tree, panMap = true) {
    selectedTree.value = tree;

    // Update markers icons to reflect selection highlight
    markersMap.forEach((marker, treeId) => {
        const currentTree = props.trees.find(t => t.id === treeId);
        if (currentTree) {
            marker.setIcon(createCustomIcon(currentTree, treeId === tree.id));
        }
    });

    if (panMap && map) {
        const lat = parseFloat(tree.location?.public_latitude || tree.location?.latitude || -6.2088);
        const lng = parseFloat(tree.location?.public_longitude || tree.location?.longitude || 106.8456);

        if (!isNaN(lat) && !isNaN(lng)) {
            map.flyTo([lat, lng], 15, { duration: 1 });
            const targetMarker = markersMap.get(tree.id);
            if (targetMarker) {
                targetMarker.openPopup();
            }
        }
    }
}

function fitAllTrees() {
    if (!map) return;
    const bounds = L.latLngBounds([]);
    filteredTrees.value.forEach((tree) => {
        const lat = parseFloat(tree.location?.public_latitude || tree.location?.latitude);
        const lng = parseFloat(tree.location?.public_longitude || tree.location?.longitude);
        if (!isNaN(lat) && !isNaN(lng)) {
            bounds.extend([lat, lng]);
        }
    });

    if (bounds.isValid()) {
        map.fitBounds(bounds, { padding: [50, 50], maxZoom: 14 });
    }
}

onMounted(() => {
    nextTick(() => {
        initMap();
    });
});

onUnmounted(() => {
    if (map) {
        map.remove();
        map = null;
    }
});

watch([filteredTrees, statusFilter], () => {
    renderMarkers();
});
</script>

<template>
    <Head title="Peta Pohon Nyata — Lintasan × BioTag" />

    <PublicLayout>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-8">
            <!-- Header Section -->
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
                <div class="space-y-2">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-[#EAF7EE] text-[#166534] text-xs font-bold border border-emerald-200">
                        <!-- Privacy Leaflet Icon -->
                        <svg class="w-3.5 h-3.5 text-[#166534]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                        <span>Peta Interaktif Nyata</span>
                        <span>•</span>
                        <span>BioTag GeoEngine (Privacy Preserved)</span>
                    </div>

                    <h1 class="text-3xl sm:text-4xl font-black text-slate-900 tracking-tight">
                        Peta Sebaran Pohon Komunitas
                    </h1>
                    <p class="text-sm text-slate-600 max-w-2xl font-normal leading-relaxed">
                        Peta geografis nyata yang memantau lokasi pohon-pohon binaan sekolah dan komunitas. Koordinat publik disajikan dengan presisi tergeneralisasi untuk menjaga keselamatan dan privasi lingkungan anak.
                    </p>
                </div>

                <!-- Filters -->
                <div class="flex flex-wrap items-center gap-3">
                    <input
                        v-model="searchQuery"
                        type="text"
                        placeholder="Cari nama pohon / kode..."
                        class="px-4 py-2.5 rounded-2xl border border-slate-200 text-xs sm:text-sm focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 w-56 bg-white shadow-xs"
                    />

                    <select
                        v-model="statusFilter"
                        class="px-3.5 py-2.5 rounded-2xl border border-slate-200 text-xs sm:text-sm focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 bg-white text-slate-700 shadow-xs"
                    >
                        <option value="all">Semua Status ({{ trees.length }})</option>
                        <option value="available">Tersedia untuk Diadopsi</option>
                        <option value="adopted">Sudah Diadopsi</option>
                    </select>
                </div>
            </div>

            <!-- Main Map & Detail Container -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                <!-- Left: REAL INTERACTIVE LEAFLET MAP -->
                <div class="lg:col-span-7 bg-white rounded-3xl border border-slate-200 p-5 shadow-xs space-y-4">
                    <!-- Map Controls Toolbar -->
                    <div class="flex flex-wrap items-center justify-between gap-3 pb-3 border-b border-slate-100">
                        <div class="flex items-center gap-2">
                            <span class="inline-flex items-center gap-1.5 text-xs font-extrabold text-slate-800">
                                <span class="w-2.5 h-2.5 rounded-full bg-emerald-600 animate-pulse"></span>
                                Peta Nyata Aktif
                            </span>
                            <span class="text-[11px] text-slate-400 font-mono">({{ filteredTrees.length }} Titik Pohon)</span>
                        </div>

                        <!-- Layer & Reset Buttons -->
                        <div class="flex items-center gap-2">
                            <button
                                @click="switchTileMode('streets')"
                                class="px-2.5 py-1 rounded-xl text-xs font-bold transition-colors"
                                :class="mapMode === 'streets' ? 'bg-[#166534] text-white shadow-xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'"
                            >
                                Peta Jalan
                            </button>
                            <button
                                @click="switchTileMode('satellite')"
                                class="px-2.5 py-1 rounded-xl text-xs font-bold transition-colors"
                                :class="mapMode === 'satellite' ? 'bg-[#166534] text-white shadow-xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'"
                            >
                                Satelit Asli
                            </button>
                            <button
                                @click="fitAllTrees"
                                class="p-1 px-2 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs font-bold"
                                title="Fokuskan Semua Pohon"
                            >
                                ⛶ Reset Zoom
                            </button>
                        </div>
                    </div>

                    <!-- Real Leaflet Map DOM Container -->
                    <div class="relative w-full h-[450px] sm:h-[480px] rounded-2xl overflow-hidden border border-slate-200 shadow-inner">
                        <div ref="mapContainer" class="w-full h-full z-0"></div>

                        <!-- Legend overlay at bottom-left of the real map -->
                        <div class="absolute bottom-3 left-3 z-1000 bg-white/95 backdrop-blur-md px-3.5 py-2 rounded-xl border border-slate-200 text-[11px] text-slate-700 shadow-md flex items-center gap-3">
                            <span class="flex items-center gap-1.5 font-bold">
                                <span class="w-3 h-3 rounded-full bg-[#166534]"></span>
                                Siap Diadopsi
                            </span>
                            <span class="flex items-center gap-1.5 font-bold">
                                <span class="w-3 h-3 rounded-full bg-amber-600"></span>
                                Diadopsi
                            </span>
                            <span class="hidden sm:inline text-slate-400 font-normal">• Klik pin untuk detail</span>
                        </div>
                    </div>

                    <p class="text-xs text-slate-500 italic text-center">
                        Peta geografis menggunakan teknologi OpenStreetMap & CARTO Geodata. Klik salah satu penanda pohon di peta untuk meninjau informasi lengkapnya.
                    </p>
                </div>

                <!-- Right: Selected Tree Detail & List -->
                <div class="lg:col-span-5 space-y-6">
                    <!-- Tree Detail Card -->
                    <div v-if="selectedTree" class="p-6 rounded-3xl bg-white border border-slate-200 shadow-xs space-y-5">
                        <div class="flex justify-between items-start">
                            <div>
                                <span class="text-xs font-bold font-mono px-2.5 py-1 rounded-lg bg-emerald-100 text-emerald-950">
                                    {{ selectedTree.tree_code }}
                                </span>
                                <h3 class="text-xl font-black text-slate-900 mt-2">{{ selectedTree.common_name }}</h3>
                                <p class="text-xs text-slate-500 italic">{{ selectedTree.species_name }}</p>
                            </div>
                            <span
                                class="text-xs font-bold px-2.5 py-1 rounded-full shrink-0"
                                :class="selectedTree.status === 'adopted' ? 'bg-amber-100 text-amber-900' : 'bg-emerald-100 text-emerald-900'"
                            >
                                {{ selectedTree.status === 'adopted' ? 'Sudah Diadopsi' : 'Tersedia' }}
                            </span>
                        </div>

                        <p class="text-xs text-slate-600 leading-relaxed">{{ selectedTree.description }}</p>

                        <div class="grid grid-cols-2 gap-3 pt-1 text-xs">
                            <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-150">
                                <span class="text-[10px] font-semibold text-slate-500 uppercase">Serapan Karbon</span>
                                <p class="text-base font-black text-[#166534]">{{ selectedTree.co2_absorption_kg_year }} kg/th</p>
                            </div>
                            <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-150">
                                <span class="text-[10px] font-semibold text-slate-500 uppercase">Lokasi Penanaman</span>
                                <p class="text-xs font-bold text-slate-700 truncate mt-0.5">{{ selectedTree.location?.address_description || 'Area Komunitas' }}</p>
                            </div>
                        </div>

                        <div v-if="selectedTree.status !== 'adopted'" class="pt-2">
                            <Link :href="route('user.dashboard')" class="w-full inline-flex justify-center items-center gap-2 py-3 rounded-2xl bg-[#166534] hover:bg-[#14532d] text-white font-extrabold text-xs shadow-sm transition-all active:scale-95">
                                <span>Pindai QR Pohon Ini untuk Mengadopsi</span>
                            </Link>
                        </div>
                        <div v-else class="p-3.5 rounded-2xl bg-[#EAF7EE] border border-emerald-200 text-xs text-[#166534] flex items-center gap-2">
                            <svg class="w-4 h-4 text-[#166534] shrink-0" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            <span>Pohon ini dirawat aktif oleh keluarga: <strong class="font-bold">{{ selectedTree.current_adoption?.user?.name || 'Siswa Komunitas' }}</strong></span>
                        </div>
                    </div>

                    <!-- Filtered Trees Interactive List -->
                    <div class="rounded-3xl bg-white border border-slate-200 p-5 shadow-xs space-y-3">
                        <div class="flex justify-between items-center">
                            <h4 class="text-xs font-black uppercase tracking-wider text-slate-500">
                                Daftar Pohon di Peta ({{ filteredTrees.length }})
                            </h4>
                            <span class="text-[11px] text-slate-400">Klik untuk sorot di peta</span>
                        </div>

                        <div class="space-y-2 max-h-72 overflow-y-auto pr-1">
                            <div
                                v-for="tree in filteredTrees"
                                :key="tree.id"
                                @click="selectTree(tree, true)"
                                class="p-3 rounded-2xl border cursor-pointer transition-all flex items-center justify-between"
                                :class="selectedTree?.id === tree.id ? 'border-[#166534] bg-[#EAF7EE]/60 shadow-xs ring-1 ring-[#166534]' : 'border-slate-100 hover:bg-slate-50'"
                            >
                                <div class="space-y-0.5">
                                    <p class="text-xs font-extrabold text-slate-900">{{ tree.common_name }}</p>
                                    <p class="text-[10px] text-slate-500 font-mono">{{ tree.tree_code }} • {{ tree.species_name }}</p>
                                </div>
                                <span
                                    class="text-[10px] font-bold px-2 py-0.5 rounded-full"
                                    :class="tree.status === 'adopted' ? 'bg-amber-100 text-amber-800' : 'bg-emerald-100 text-emerald-800'"
                                >
                                    {{ tree.status === 'adopted' ? 'Diadopsi' : 'Tersedia' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </PublicLayout>
</template>

<style>
/* Leaflet marker container cleanup */
.custom-leaflet-tree-marker {
    background: transparent;
    border: none;
}
.leaflet-popup-content-wrapper {
    border-radius: 1rem;
    box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
}
.leaflet-popup-content {
    margin: 12px 14px;
}
</style>
