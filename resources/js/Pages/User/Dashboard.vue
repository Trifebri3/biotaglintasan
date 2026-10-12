<script setup>
import UserLayout from '@/Layouts/UserLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

const props = defineProps({
    adoptions: {
        type: Array,
        default: () => [],
    },
    greenActions: {
        type: Array,
        default: () => [],
    },
    completedActionIds: {
        type: Array,
        default: () => [],
    },
    carbonSummary: {
        type: Object,
        default: () => ({ total_co2e_kg: 3820, entries_count: 8, by_category: {} }),
    },
    recentEmissions: {
        type: Array,
        default: () => [],
    },
    membership: {
        type: Object,
        default: null,
    },
});

// Modals
const showCalculateModal = ref(false);
const showQrModal = ref(false);
const showPhotoModal = ref(false);
const activePhoto = ref(null);

// Forms
const qrForm = useForm({
    token: '',
});

function submitQr() {
    if (!qrForm.token) return;
    qrForm.get(route('user.scan-preview', { token: qrForm.token }));
}

const actionForm = useForm({
    green_action_id: null,
});

function toggleAction(actionId) {
    actionForm.green_action_id = actionId;
    actionForm.post(route('user.log-action'), {
        preserveScroll: true,
    });
}

const emissionForm = useForm({
    category: 'electricity',
    activity_value: '',
    period_start: new Date(new Date().getFullYear(), new Date().getMonth(), 1).toISOString().split('T')[0],
    period_end: new Date().toISOString().split('T')[0],
    notes: '',
});

function submitEmission() {
    emissionForm.post(route('user.record-emission'), {
        preserveScroll: true,
        onSuccess: () => {
            emissionForm.reset('activity_value', 'notes');
            showCalculateModal.value = false;
        },
    });
}

function openPhoto(obs, tree) {
    activePhoto.value = {
        url: obs.photo_url || '/images/tree-sample-1.jpg',
        treeName: tree.common_name,
        treeCode: tree.tree_code,
        date: obs.observed_at ? new Date(obs.observed_at).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' }) : 'Baru saja',
        officer: obs.observer?.name || 'Pak Hendra (Pengawas Pohon BioTag)',
        notes: obs.notes || 'Pohon dalam pemantauan rutin berkala oleh pengawas lapangan.',
        condition: obs.condition_status,
    };
    showPhotoModal.value = true;
}

// Donut Chart breakdown percentages (Exactly matching Image 1: Listrik 48%, Mobil 32%, Motor 12%, LPG 8%)
const breakdown = [
    { label: 'Listrik', pct: 48, tons: '1,83', color: '#F59E0B', textClass: 'text-amber-600', bgClass: 'bg-amber-500' },
    { label: 'Transportasi (Mobil)', pct: 32, tons: '1,22', color: '#2563EB', textClass: 'text-blue-600', bgClass: 'bg-blue-600' },
    { label: 'Kendaraan (Motor)', pct: 12, tons: '0,46', color: '#16A34A', textClass: 'text-emerald-600', bgClass: 'bg-emerald-600' },
    { label: 'LPG', pct: 8, tons: '0,31', color: '#EA580C', textClass: 'text-orange-600', bgClass: 'bg-orange-600' },
];

// SVG Donut Calculations
const circumference = 2 * Math.PI * 40; // 251.327
let accumulated = 0;
const slices = breakdown.map((item) => {
    const strokeDashoffset = circumference - (item.pct / 100) * circumference;
    const rotate = (accumulated / 100) * 360;
    accumulated += item.pct;
    return {
        ...item,
        strokeDashoffset,
        rotate,
    };
});
</script>

<template>
    <Head title="Keluarga Andi — Jejak Hijau Keluargaku" />

    <UserLayout>
        <div class="space-y-6 sm:space-y-8 pb-12">
            <!-- 1. Hero Greeting Section (Matching Image 1: Light, Airy & Friendly) -->
            <div class="flex flex-col md:flex-row items-center justify-between gap-6 pt-1 pb-2">
                <div class="space-y-3 max-w-xl">
                    <h1 class="text-3xl sm:text-4xl lg:text-[42px] font-black text-[#153424] tracking-tight leading-tight">
                        Selamat datang,<br />
                        <span class="inline-flex items-center gap-2.5">
                            <span>Keluarga Andi</span>
                            <!-- Clean Leaf Icon from Image 1 -->
                            <svg class="w-7 h-7 sm:w-8 sm:h-8 text-[#23813F]" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M17 8C8 10 5.9 16.17 3.82 21.34l1.89.66.95-2.3c.48.17.98.3 1.34.3C19 20 22 3 22 3c-1 2-8 2.25-13 3.75C7 7.25 5 9 5 11c0 2 2 3 2 3s2.5-3 10-6z" />
                            </svg>
                        </span>
                    </h1>

                    <p class="text-sm sm:text-base text-slate-600 leading-relaxed font-normal max-w-lg">
                        Yuk, kenali jejak emisi keluarga kita dan ambil aksi bersama untuk masa depan yang lebih baik.
                    </p>
                </div>

                <!-- Family Illustration from Image 1 -->
                <div class="shrink-0 w-60 sm:w-72 lg:w-84">
                    <img
                        src="/images/family-green-hero.jpg"
                        alt="Keluarga Andi"
                        class="w-full h-auto rounded-3xl object-cover shadow-xs border border-emerald-100/80"
                    />
                </div>
            </div>

            <!-- 2. Card 1: JEJAK EMISI KELUARGA KITA & DARI MANA EMISI BERASAL? (Matching Image 1) -->
            <div class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-8 shadow-xs">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                    <!-- Left Column: Jejak Emisi Keluarga Kita -->
                    <div class="lg:col-span-5 space-y-5">
                        <div class="flex items-center gap-2">
                            <span class="text-xs sm:text-sm font-extrabold tracking-wider text-slate-800 uppercase">
                                JEJAK EMISI KELUARGA KITA
                            </span>
                            <!-- Info circle icon -->
                            <svg class="w-4 h-4 text-slate-400 cursor-pointer hover:text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>

                        <!-- 3.82 TON CO2e per tahun -->
                        <div class="flex items-baseline gap-3">
                            <span class="text-5xl sm:text-6xl font-black text-[#153424] tracking-tight">3,82</span>
                            <div>
                                <span class="block text-sm sm:text-base font-extrabold text-slate-800">TON CO₂e</span>
                                <span class="text-xs text-slate-500 font-medium">per tahun</span>
                            </div>
                        </div>

                        <!-- Pill: Terima kasih! Anda sudah lebih baik dari 43% keluarga lainnya. -->
                        <div class="inline-flex items-center gap-2 bg-[#EAF7EE] text-[#166534] px-3.5 py-2 rounded-2xl text-xs font-semibold">
                            <svg class="w-4 h-4 text-[#166534] shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 2a2 2 0 00-2 2v11a3 3 0 106 0V4a2 2 0 00-2-2H4zm1 14a1 1 0 100-2 1 1 0 000 2zm5-1.757l4.9-4.9a2 2 0 000-2.828L13.485 5.1a2 2 0 00-2.828 0L10 5.757v8.486z" clip-rule="evenodd" />
                            </svg>
                            <span>Terima kasih! Anda sudah lebih baik dari 43% keluarga lainnya.</span>
                        </div>

                        <!-- Action Button: Lihat Detail Hasil > -->
                        <div>
                            <button
                                @click="showCalculateModal = true"
                                class="w-full sm:w-auto inline-flex items-center justify-between gap-6 px-6 py-3 rounded-2xl bg-[#166534] hover:bg-[#14532d] text-white font-bold text-xs sm:text-sm shadow-sm transition-all"
                            >
                                <span>Lihat Detail Hasil</span>
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Center Divider Line for Desktop -->
                    <div class="hidden lg:block lg:col-span-1 flex justify-center">
                        <div class="w-px h-48 bg-slate-100"></div>
                    </div>

                    <!-- Right Column: Dari Mana Emisi Berasal? & Donut Chart -->
                    <div class="lg:col-span-6 space-y-4">
                        <div class="text-xs sm:text-sm font-extrabold tracking-wider text-slate-800 uppercase">
                            DARI MANA EMISI BERASAL?
                        </div>

                        <div class="flex flex-col sm:flex-row items-center gap-6">
                            <!-- Donut Chart with CO2 Cloud Icon in center -->
                            <div class="relative w-44 h-44 shrink-0 flex items-center justify-center">
                                <svg class="w-full h-full -rotate-90" viewBox="0 0 100 100">
                                    <circle cx="50" cy="50" r="40" fill="transparent" stroke="#F1F5F9" stroke-width="12" />
                                    <circle
                                        v-for="(slice, i) in slices"
                                        :key="i"
                                        cx="50"
                                        cy="50"
                                        r="40"
                                        fill="transparent"
                                        :stroke="slice.color"
                                        stroke-width="12"
                                        :stroke-dasharray="circumference"
                                        :stroke-dashoffset="slice.strokeDashoffset"
                                        :transform="`rotate(${slice.rotate} 50 50)`"
                                        stroke-linecap="round"
                                    />
                                </svg>

                                <!-- Cloud icon in center of donut -->
                                <div class="absolute inset-0 flex flex-col items-center justify-center text-center pointer-events-none">
                                    <svg class="w-8 h-8 text-slate-400 mb-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 00-9.78 2.096A4.001 4.001 0 003 15z" />
                                    </svg>
                                    <span class="text-[10px] font-extrabold text-slate-500 leading-none">CO₂</span>
                                    <span class="text-[9px] text-slate-400 font-semibold leading-none">CO₂e</span>
                                </div>
                            </div>

                            <!-- Legend List exactly matching Image 1 -->
                            <div class="space-y-2.5 w-full sm:w-auto text-xs">
                                <!-- 1. Listrik -->
                                <div class="flex items-center justify-between sm:gap-6">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                                        <span class="font-semibold text-slate-700">Listrik</span>
                                    </div>
                                    <span class="font-black text-slate-900">48%</span>
                                </div>

                                <!-- 2. Transportasi (Mobil) -->
                                <div class="flex items-center justify-between sm:gap-6">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 17a2 2 0 100-4 2 2 0 000 4zm8 0a2 2 0 100-4 2 2 0 000 4zM5 11l2-5h10l2 5M3 17h2m14 0h2m-4-6H7" /></svg>
                                        <span class="font-semibold text-slate-700">Transportasi (Mobil)</span>
                                    </div>
                                    <span class="font-black text-slate-900">32%</span>
                                </div>

                                <!-- 3. Kendaraan (Motor) -->
                                <div class="flex items-center justify-between sm:gap-6">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" /></svg>
                                        <span class="font-semibold text-slate-700">Kendaraan (Motor)</span>
                                    </div>
                                    <span class="font-black text-slate-900">12%</span>
                                </div>

                                <!-- 4. LPG -->
                                <div class="flex items-center justify-between sm:gap-6">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-orange-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343a7.975 7.975 0 010 11.314z" /></svg>
                                        <span class="font-semibold text-slate-700">LPG</span>
                                    </div>
                                    <span class="font-black text-slate-900">8%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 3. Card 2: AYO MULAI DARI SATU AKSI KECIL (Matching Image 1) -->
            <div class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-8 shadow-xs space-y-6">
                <div>
                    <h3 class="text-base sm:text-lg font-black text-slate-900 tracking-tight">
                        AYO MULAI DARI SATU AKSI KECIL
                    </h3>
                    <p class="text-xs sm:text-sm text-slate-500 mt-1">
                        Pilih satu aksi sederhana untuk kita lakukan bersama hari ini.
                    </p>
                </div>

                <!-- 5 Action Pills Row -->
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4">
                    <button
                        v-for="action in greenActions"
                        :key="action.id"
                        @click="toggleAction(action.id)"
                        class="p-5 rounded-2xl border text-center transition-all flex flex-col items-center justify-between gap-3 group"
                        :class="completedActionIds.includes(action.id)
                            ? 'bg-[#EAF7EE] border-[#23813F]/50 shadow-xs'
                            : 'bg-white border-slate-150 hover:border-slate-300 hover:shadow-xs'"
                    >
                        <!-- Circular Icon Container -->
                        <div
                            class="w-13 h-13 rounded-2xl flex items-center justify-center transition-transform group-hover:scale-105"
                            :class="{
                                'bg-amber-100/80 text-amber-600': action.category === 'energy',
                                'bg-blue-100/80 text-blue-600': action.category === 'travel',
                                'bg-emerald-100/80 text-emerald-600': action.category === 'waste',
                                'bg-cyan-100/80 text-cyan-600': action.category === 'water',
                                'bg-lime-100/80 text-lime-700': action.category !== 'energy' && action.category !== 'travel' && action.category !== 'waste' && action.category !== 'water',
                            }"
                        >
                            <!-- Category SVGs -->
                            <svg v-if="action.category === 'energy'" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" /></svg>
                            <svg v-else-if="action.category === 'travel'" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 17a2 2 0 100-4 2 2 0 000 4zm8 0a2 2 0 100-4 2 2 0 000 4zM5 11l2-5h10l2 5M3 17h2m14 0h2m-4-6H7" /></svg>
                            <svg v-else-if="action.category === 'waste'" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>
                            <svg v-else-if="action.category === 'water'" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" /></svg>
                            <svg v-else class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M17 8C8 10 5.9 16.17 3.82 21.34l1.89.66.95-2.3c.48.17.98.3 1.34.3C19 20 22 3 22 3c-1 2-8 2.25-13 3.75C7 7.25 5 9 5 11c0 2 2 3 2 3s2.5-3 10-6z" /></svg>
                        </div>

                        <!-- Action Name -->
                        <div class="space-y-1">
                            <span class="block text-xs sm:text-sm font-bold text-slate-800 leading-tight">
                                {{ action.title }}
                            </span>
                            <span
                                v-if="completedActionIds.includes(action.id)"
                                class="inline-flex items-center gap-1 text-[11px] font-bold text-[#166534]"
                            >
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" /></svg>
                                Selesai
                            </span>
                            <span v-else class="text-[11px] text-slate-400 font-medium">
                                +{{ action.impact_points }} Poin
                            </span>
                        </div>
                    </button>
                </div>

                <!-- Carousel pagination dots from Image 1 -->
                <div class="flex items-center justify-center gap-1.5 pt-1">
                    <span class="w-2 h-2 rounded-full bg-[#166534]"></span>
                    <span class="w-1.5 h-1.5 rounded-full bg-slate-300"></span>
                    <span class="w-1.5 h-1.5 rounded-full bg-slate-300"></span>
                    <span class="w-1.5 h-1.5 rounded-full bg-slate-300"></span>
                </div>
            </div>

            <!-- 4. Card 3: Row with 2 Cards (AKTIVITAS TERAKHIR & PERJALANAN KELUARGA KITA) -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 sm:gap-8 items-stretch">
                <!-- Left: AKTIVITAS TERAKHIR -->
                <div class="lg:col-span-5 bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-7 shadow-xs flex flex-col justify-between space-y-5">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-slate-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <h4 class="text-xs sm:text-sm font-extrabold tracking-wider text-slate-800 uppercase">
                            AKTIVITAS TERAKHIR
                        </h4>
                    </div>

                    <!-- Inner Card with Calculator Icon -->
                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-150 flex items-center gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-white border border-slate-200 text-[#166534] flex items-center justify-center shrink-0 shadow-xs">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div>
                            <span class="block text-xs text-slate-500 font-medium">Perhitungan terakhir</span>
                            <span class="text-sm font-black text-slate-900">20 Mei 2024</span>
                        </div>
                    </div>

                    <!-- Button: Hitung Ulang Sekarang > -->
                    <button
                        @click="showCalculateModal = true"
                        class="w-full py-3 px-5 rounded-2xl bg-white hover:bg-slate-50 border border-slate-200 text-slate-800 font-bold text-xs sm:text-sm flex items-center justify-between transition-colors shadow-xs"
                    >
                        <span>Hitung Ulang Sekarang</span>
                        <svg class="w-4 h-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                </div>

                <!-- Right: PERJALANAN KELUARGA KITA -->
                <div class="lg:col-span-7 bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-7 shadow-xs flex flex-col justify-between space-y-4">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-slate-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                        </svg>
                        <h4 class="text-xs sm:text-sm font-extrabold tracking-wider text-slate-800 uppercase">
                            PERJALANAN KELUARGA KITA
                        </h4>
                    </div>

                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div class="space-y-1">
                            <span class="block text-xs text-slate-500 font-medium">Jejak emisi tahun ini</span>
                            <div class="flex items-center gap-2.5">
                                <span class="text-2xl sm:text-3xl font-black text-[#153424]">3,82</span>
                                <span class="text-xs sm:text-sm font-extrabold text-slate-700">TON CO₂e</span>
                            </div>
                            <div class="flex items-center gap-2 pt-1">
                                <span class="inline-flex items-center gap-1 bg-[#EAF7EE] text-[#166534] px-2.5 py-0.5 rounded-full text-xs font-bold">
                                    ▼ 0,42 ton
                                </span>
                                <span class="text-xs text-slate-500 font-medium">Dibanding tahun lalu</span>
                            </div>
                        </div>

                        <!-- Line chart simulation matching Image 1 -->
                        <div class="w-full sm:w-56 space-y-1">
                            <div class="h-20 flex items-center justify-center">
                                <svg class="w-full h-full" viewBox="0 0 200 60" fill="none">
                                    <!-- Trend line sloping down -->
                                    <path d="M 10 15 Q 50 20, 90 35 T 150 48 T 190 52" stroke="#23813F" stroke-width="3" stroke-linecap="round" />
                                    <!-- Dots on points -->
                                    <circle cx="10" cy="15" r="4" fill="#23813F" stroke="#FFFFFF" stroke-width="2" />
                                    <circle cx="55" cy="22" r="4" fill="#23813F" stroke="#FFFFFF" stroke-width="2" />
                                    <circle cx="100" cy="38" r="4" fill="#23813F" stroke="#FFFFFF" stroke-width="2" />
                                    <circle cx="145" cy="47" r="4" fill="#23813F" stroke="#FFFFFF" stroke-width="2" />
                                    <circle cx="190" cy="52" r="4" fill="#23813F" stroke="#FFFFFF" stroke-width="2" />
                                </svg>
                            </div>
                            <div class="flex justify-between text-[11px] font-bold text-slate-400 px-1">
                                <span>2023</span>
                                <span>2024</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 5. Card 4: Tahukah kamu? (Matching Image 1) -->
            <div class="rounded-3xl bg-[#EAF7EE]/70 border border-emerald-100 p-6 sm:p-7 shadow-xs">
                <div class="flex flex-col md:flex-row items-center justify-between gap-6">
                    <!-- Left: Child holding plant illustration -->
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl overflow-hidden shrink-0 shadow-xs border border-white">
                            <img
                                src="/images/child-holding-plant.jpg"
                                alt="Anak Merawat Tanaman"
                                class="w-full h-full object-cover"
                            />
                        </div>

                        <div class="space-y-1 max-w-md">
                            <h4 class="text-sm sm:text-base font-extrabold text-[#153424]">Tahukah kamu?</h4>
                            <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                                Satu pohon dapat menyerap sekitar 20 kg CO₂ setiap tahun.
                            </p>
                        </div>
                    </div>

                    <!-- Center Divider -->
                    <div class="hidden md:block w-px h-12 bg-emerald-200/80"></div>

                    <!-- Right: Call to action with tree icon -->
                    <div class="flex items-center justify-between md:justify-end gap-5 w-full md:w-auto">
                        <p class="text-xs sm:text-sm text-slate-700 font-medium max-w-xs leading-relaxed">
                            Bersama LINTASAN, kita menanam harapan untuk bumi yang lebih hijau.
                        </p>

                        <Link
                            :href="route('public.education')"
                            class="w-10 h-10 rounded-full bg-[#166534] hover:bg-[#14532d] text-white flex items-center justify-center shrink-0 shadow-sm transition-transform active:scale-95"
                        >
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                            </svg>
                        </Link>
                    </div>
                </div>
            </div>

            <!-- 6. Card 5: POHON ADOPSI & UPDATE FOTO DARI PETUGAS / PENGAWAS LAPANGAN (Role Petugas) -->
            <div class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-8 shadow-xs space-y-6">
                <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-3 border-b border-slate-100 pb-4">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-bold uppercase tracking-wider text-emerald-800 bg-emerald-50 px-2.5 py-1 rounded-md">
                                BioTag Tree Monitor
                            </span>
                            <span class="text-xs font-semibold px-2 py-0.5 rounded-md bg-sky-50 text-sky-800 border border-sky-100">
                                Update Foto Lapangan
                            </span>
                        </div>
                        <h2 class="text-xl sm:text-2xl font-black text-slate-900 mt-1">
                            Pohon Adopsi & Update Foto Terkini dari Petugas Lapangan
                        </h2>
                        <p class="text-xs text-slate-500 mt-0.5">
                            Petugas pengawas lapangan rutin melakukan scan QR dan mengunggah foto perkembangan pohon yang diadopsi anak.
                        </p>
                    </div>

                    <button
                        @click="showQrModal = true"
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-2xl bg-emerald-50 hover:bg-emerald-100 text-emerald-900 font-bold text-xs border border-emerald-200 transition-colors self-start sm:self-auto"
                    >
                        <svg class="w-4 h-4 text-emerald-700" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                        <span>+ Adopsi Pohon Baru</span>
                    </button>
                </div>

                <!-- Empty State -->
                <div v-if="adoptions.length === 0" class="text-center py-12 border-2 border-dashed border-slate-200 rounded-3xl space-y-3">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-700 flex items-center justify-center mx-auto">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M17 8C8 10 5.9 16.17 3.82 21.34l1.89.66.95-2.3c.48.17.98.3 1.34.3C19 20 22 3 22 3c-1 2-8 2.25-13 3.75C7 7.25 5 9 5 11c0 2 2 3 2 3s2.5-3 10-6z" /></svg>
                    </div>
                    <h3 class="text-base font-bold text-slate-800">Belum ada pohon yang diadopsi</h3>
                    <p class="text-xs text-slate-500 max-w-sm mx-auto">
                        Pindai kode QR BioTag pada plang pohon di sekolahmu atau masukkan token seperti <span class="font-mono text-emerald-800 font-bold">qr_mangga_golek_2026</span>.
                    </p>
                </div>

                <!-- Adopted Trees Showcase -->
                <div v-else class="space-y-6">
                    <div
                        v-for="adoption in adoptions"
                        :key="adoption.id"
                        class="p-6 rounded-3xl bg-slate-50 border border-slate-200/80 space-y-5"
                    >
                        <!-- Tree Header Info -->
                        <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-3">
                            <div class="space-y-1">
                                <div class="flex items-center gap-2">
                                    <span class="text-xs font-mono font-bold px-2.5 py-0.5 rounded-md bg-emerald-200/80 text-emerald-950">
                                        {{ adoption.tree.tree_code }}
                                    </span>
                                    <span class="text-xs font-bold text-slate-400">•</span>
                                    <span class="text-xs text-slate-600 font-medium">
                                        {{ adoption.tree.location?.address_description || 'Kebun Belajar Siswa' }}
                                    </span>
                                </div>
                                <h3 class="text-xl font-black text-slate-900">
                                    {{ adoption.tree.common_name }}
                                </h3>
                                <p class="text-xs text-slate-500 italic">
                                    {{ adoption.tree.species_name }}
                                </p>
                            </div>

                            <div class="flex items-center gap-2 bg-white px-4 py-2 rounded-2xl border border-slate-200 shrink-0">
                                <div class="text-right">
                                    <span class="block text-xs font-bold text-slate-500">Daya Serap Karbon</span>
                                    <span class="text-sm font-black text-emerald-700">{{ adoption.tree.co2_absorption_kg_year }} kg CO₂e / tahun</span>
                                </div>
                            </div>
                        </div>

                        <!-- Live Photo Updates Stream from Officer -->
                        <div class="space-y-3">
                            <div class="flex justify-between items-center">
                                <h4 class="text-xs font-bold uppercase tracking-wider text-slate-700 flex items-center gap-1.5">
                                    <svg class="w-4 h-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    Laporan & Foto Kondisi dari Pengawas Lapangan
                                </h4>
                                <span class="text-[11px] text-slate-400">
                                    {{ adoption.tree.observations?.length || 0 }} Foto Terverifikasi
                                </span>
                            </div>

                            <!-- Photo Cards Grid -->
                            <div v-if="adoption.tree.observations && adoption.tree.observations.length > 0" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                                <div
                                    v-for="(obs, idx) in adoption.tree.observations"
                                    :key="obs.id || idx"
                                    @click="openPhoto(obs, adoption.tree)"
                                    class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-xs hover:shadow-md transition-all cursor-pointer group flex flex-col"
                                >
                                    <!-- Photo Thumbnail -->
                                    <div class="relative aspect-4/3 overflow-hidden bg-slate-100">
                                        <img
                                            :src="obs.photo_url || '/images/tree-sample-1.jpg'"
                                            :alt="adoption.tree.common_name"
                                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                        />
                                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-80 group-hover:opacity-60 transition-opacity"></div>

                                        <!-- Badge Status -->
                                        <div class="absolute top-2.5 left-2.5">
                                            <span
                                                class="text-[10px] font-black px-2 py-0.5 rounded-md uppercase tracking-wider backdrop-blur-xs text-white"
                                                :class="obs.condition_status === 'thriving' ? 'bg-emerald-600/90' : 'bg-sky-600/90'"
                                            >
                                                {{ obs.condition_status === 'thriving' ? 'Sangat Subur' : 'Sehat Terawat' }}
                                            </span>
                                        </div>

                                        <div class="absolute bottom-2.5 left-2.5 right-2.5 text-white">
                                            <span class="text-[10px] font-medium opacity-90 block">
                                                {{ obs.observed_at ? new Date(obs.observed_at).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' }) : 'Terbaru' }}
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Report Notes -->
                                    <div class="p-3.5 space-y-2 flex-1 flex flex-col justify-between">
                                        <p class="text-xs text-slate-700 line-clamp-2 leading-relaxed">
                                            {{ obs.notes }}
                                        </p>

                                        <div class="pt-2 border-t border-slate-100 flex items-center justify-between text-[11px]">
                                            <span class="font-semibold text-emerald-800 truncate">
                                                {{ obs.observer?.name || 'Pak Hendra (Petugas BioTag)' }}
                                            </span>
                                            <span class="text-slate-400 group-hover:text-emerald-700 font-bold shrink-0">
                                                Lihat Foto ↗
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div v-else class="p-4 rounded-2xl bg-white border border-slate-200 text-xs text-slate-500 text-center">
                                Petugas pengawas sedang menjadwalkan kunjungan inspeksi berkala untuk pohon ini. Foto akan segera tampil di sini.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 7. Mobile Dock Navigation Bar (Matching Image 1: Beranda, Hasil, Hitung, Aksi, Akun) -->
        <!-- Docked at the bottom on mobile (md:hidden), completely clean on desktop -->
        <nav class="md:hidden fixed bottom-0 inset-x-0 z-40 bg-white/95 backdrop-blur-md border-t border-slate-200/90 shadow-[0_-4px_25px_rgba(0,0,0,0.06)] px-4 py-2 flex items-center justify-around">
            <Link :href="route('user.dashboard')" class="flex flex-col items-center gap-0.5 text-[#166534]">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" /></svg>
                <span class="text-[9px] font-extrabold">Beranda</span>
            </Link>

            <Link :href="route('public.map')" class="flex flex-col items-center gap-0.5 text-slate-400 hover:text-slate-600">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg>
                <span class="text-[9px] font-bold">Hasil</span>
            </Link>

            <!-- Elevated HITUNG Button in Center -->
            <button
                @click="showCalculateModal = true"
                class="-mt-6 w-12 h-12 rounded-full bg-[#166534] text-white flex items-center justify-center shadow-lg shadow-[#166534]/30 active:scale-95 transition-transform border-3 border-white"
                title="Hitung Emisi Baru"
            >
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
            </button>

            <Link :href="route('public.education')" class="flex flex-col items-center gap-0.5 text-slate-400 hover:text-slate-600">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17 8C8 10 5.9 16.17 3.82 21.34l1.89.66.95-2.3c.48.17.98.3 1.34.3C19 20 22 3 22 3c-1 2-8 2.25-13 3.75C7 7.25 5 9 5 11c0 2 2 3 2 3s2.5-3 10-6z" /></svg>
                <span class="text-[9px] font-bold">Aksi</span>
            </Link>

            <Link :href="route('profile.edit')" class="flex flex-col items-center gap-0.5 text-slate-400 hover:text-slate-600">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                <span class="text-[9px] font-bold">Akun</span>
            </Link>
        </nav>

        <!-- MODAL: Photo Zoom Modal for Children & Family -->
        <div v-if="showPhotoModal && activePhoto" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-xs">
            <div class="bg-white rounded-3xl max-w-lg w-full overflow-hidden shadow-2xl space-y-4">
                <div class="relative aspect-4/3 bg-slate-900">
                    <img :src="activePhoto.url" :alt="activePhoto.treeName" class="w-full h-full object-cover" />
                    <button
                        @click="showPhotoModal = false"
                        class="absolute top-3 right-3 w-8 h-8 rounded-full bg-black/60 text-white flex items-center justify-center hover:bg-black transition-colors"
                    >
                        ✕
                    </button>
                </div>

                <div class="p-6 pt-0 space-y-3">
                    <div class="flex justify-between items-start">
                        <div>
                            <span class="text-[10px] font-mono font-bold px-2 py-0.5 rounded bg-emerald-100 text-emerald-900">
                                {{ activePhoto.treeCode }}
                            </span>
                            <h3 class="font-black text-lg text-slate-900 mt-1">{{ activePhoto.treeName }}</h3>
                        </div>
                        <span class="text-xs text-slate-500 font-semibold">{{ activePhoto.date }}</span>
                    </div>

                    <div class="p-3 rounded-2xl bg-slate-50 border border-slate-100 text-xs text-slate-700 leading-relaxed">
                        <strong class="text-slate-900 block mb-1">Catatan Pengawas Lapangan:</strong>
                        {{ activePhoto.notes }}
                    </div>

                    <div class="flex items-center justify-between pt-2 border-t border-slate-100 text-xs">
                        <span class="text-slate-500">
                            Petugas: <strong class="text-slate-800">{{ activePhoto.officer }}</strong>
                        </span>
                        <button
                            @click="showPhotoModal = false"
                            class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-800 font-bold"
                        >
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- MODAL: Catat Emisi Baru -->
        <div v-if="showCalculateModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs">
            <div class="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl space-y-5">
                <div class="flex justify-between items-center border-b border-slate-100 pb-3">
                    <div>
                        <h3 class="font-black text-lg text-slate-900">Catat Emisi Keluarga</h3>
                        <p class="text-xs text-slate-500">Formula standar Kementerian ESDM & IPCC</p>
                    </div>
                    <button @click="showCalculateModal = false" class="text-slate-400 hover:text-slate-600 font-bold">✕</button>
                </div>

                <form @submit.prevent="submitEmission" class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Kategori Aktivitas</label>
                        <select v-model="emissionForm.category" class="w-full px-3.5 py-2.5 rounded-2xl border border-slate-200 text-xs focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 bg-white">
                            <option value="electricity">Listrik Rumah (kWh)</option>
                            <option value="transport_motorcycle">Sepeda Motor (km)</option>
                            <option value="transport_gasoline">Mobil Bensin (liter)</option>
                            <option value="transport_public">Angkutan Umum / Bus (km)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Jumlah Pemakaian</label>
                        <input
                            v-model="emissionForm.activity_value"
                            type="number"
                            step="0.1"
                            placeholder="Contoh: 25"
                            class="w-full px-3.5 py-2.5 rounded-2xl border border-slate-200 text-xs focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500"
                            required
                        />
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Periode Awal</label>
                            <input v-model="emissionForm.period_start" type="date" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs" required />
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Periode Akhir</label>
                            <input v-model="emissionForm.period_end" type="date" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs" required />
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Catatan Tambahan (Opsional)</label>
                        <input v-model="emissionForm.notes" type="text" placeholder="Contoh: Tagihan listrik bulan ini" class="w-full px-3.5 py-2.5 rounded-2xl border border-slate-200 text-xs" />
                    </div>

                    <div class="pt-2 flex gap-3">
                        <button
                            type="button"
                            @click="showCalculateModal = false"
                            class="flex-1 py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50"
                        >
                            Batal
                        </button>
                        <button
                            type="submit"
                            :disabled="emissionForm.processing"
                            class="flex-1 py-2.5 rounded-xl bg-[#166534] hover:bg-[#14532d] text-white text-xs font-extrabold shadow-sm"
                        >
                            Simpan Emisi
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- MODAL: Scan QR / Token Adopsi Pohon -->
        <div v-if="showQrModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs">
            <div class="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl space-y-4">
                <div class="flex justify-between items-center border-b border-slate-100 pb-3">
                    <div>
                        <h3 class="font-black text-lg text-slate-900">Pindai / Input Token QR Pohon</h3>
                        <p class="text-xs text-slate-500">BioTag Secure Token Verification</p>
                    </div>
                    <button @click="showQrModal = false" class="text-slate-400 hover:text-slate-600 font-bold">✕</button>
                </div>

                <p class="text-xs text-slate-600 leading-relaxed">
                    Masukkan token yang tertera pada plang pohon di sekolahmu untuk memverifikasi dan mengadopsi:
                </p>

                <form @submit.prevent="submitQr" class="space-y-3">
                    <input
                        v-model="qrForm.token"
                        type="text"
                        placeholder="Contoh: qr_mangga_golek_2026"
                        class="w-full px-4 py-2.5 rounded-2xl border border-slate-200 text-xs sm:text-sm font-mono focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500"
                        required
                    />

                    <div class="p-3 rounded-2xl bg-slate-50 border border-slate-100 text-[11px] text-slate-500">
                        Token siap uji: <span class="font-mono font-bold text-emerald-800">qr_mangga_golek_2026</span> atau <span class="font-mono font-bold text-emerald-800">qr_tabebuya_kuning_2026</span>
                    </div>

                    <div class="flex gap-3 pt-2">
                        <button
                            type="button"
                            @click="showQrModal = false"
                            class="flex-1 py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-600"
                        >
                            Batal
                        </button>
                        <button
                            type="submit"
                            :disabled="qrForm.processing"
                            class="flex-1 py-2.5 rounded-xl bg-[#166534] hover:bg-[#14532d] text-white text-xs font-extrabold shadow-sm"
                        >
                            Periksa & Adopsi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </UserLayout>
</template>
