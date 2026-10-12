<script setup>
import PublicLayout from '@/Layouts/PublicLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    stats: {
        type: Object,
        default: () => ({ total_trees: 0, adopted_trees: 0, schools_count: 0, actions_count: 0 }),
    },
    featured_trees: {
        type: Array,
        default: () => [],
    },
});
</script>

<template>
    <Head title="Lintasan — Jejak Hijau Keluargaku" />

    <PublicLayout>
        <!-- Hero Section -->
        <section class="relative overflow-hidden pt-12 pb-20 lg:pt-20 lg:pb-28">
            <!-- Background glow accents -->
            <div class="absolute -top-24 -left-24 w-96 h-96 bg-brand-lemon/30 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute top-1/2 -right-24 w-96 h-96 bg-emerald-200/40 rounded-full blur-3xl pointer-events-none"></div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                    <div class="lg:col-span-7 space-y-6 text-left">
                        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-emerald-100/80 border border-emerald-200 text-brand-dark text-xs font-bold tracking-wide shadow-xs">
                            <span class="w-2 h-2 rounded-full bg-brand-green animate-pulse"></span>
                            The Community Space • Program Kolaborasi Lingkungan 2026
                        </div>

                        <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black tracking-tight text-brand-dark leading-[1.12]">
                            LINTASAN<br/>
                            <span class="text-brand-green">Jejak Hijau</span> Keluargaku.
                        </h1>

                        <p class="text-lg sm:text-xl text-gray-600 leading-relaxed max-w-2xl font-normal">
                            Menghubungkan pembelajaran di sekolah dengan aksi nyata di rumah. Kenali jejak karbon keluargamu, lakukan aksi kecil harian, dan rawat pohon yang kamu adopsi melalui kode QR unik.
                        </p>

                        <!-- Action Buttons -->
                        <div class="pt-4 flex flex-wrap items-center gap-4">
                            <Link :href="route('register')" class="inline-flex items-center gap-2 px-7 py-3.5 rounded-2xl bg-brand-lemon hover:bg-brand-lemon-hover text-brand-dark font-extrabold text-base shadow-lg shadow-brand-lemon/30 transition-all transform hover:-translate-y-0.5 active:translate-y-0">
                                <span>Mulai Aksi Sekarang</span>
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </Link>
                            <Link :href="route('public.map')" class="inline-flex items-center gap-2 px-6 py-3.5 rounded-2xl bg-white hover:bg-emerald-50 text-brand-dark font-bold text-base border border-emerald-200 shadow-sm transition-all">
                                <svg class="w-5 h-5 text-emerald-700" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                                <span>Jelajahi Peta Pohon</span>
                            </Link>
                        </div>

                        <!-- Brand credit & trust note -->
                        <div class="pt-3 flex items-center gap-3 text-xs text-gray-500">
                            <div class="flex -space-x-2">
                                <span class="w-7 h-7 rounded-full bg-emerald-600 text-white flex items-center justify-center font-bold text-[10px] ring-2 ring-white">YL</span>
                                <span class="w-7 h-7 rounded-full bg-brand-lemon text-brand-dark flex items-center justify-center font-bold text-[10px] ring-2 ring-white">BT</span>
                            </div>
                            <p>Yayasan Lintasan × Technology Platform by <span class="font-bold text-brand-dark">BioTag</span></p>
                        </div>
                    </div>

                    <!-- Hero Visual Card -->
                    <div class="lg:col-span-5">
                        <div class="relative mx-auto max-w-md lg:max-w-none">
                            <div class="rounded-3xl bg-white p-7 shadow-xl shadow-emerald-950/5 border border-emerald-100 space-y-6">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <span class="text-xs font-bold text-emerald-800 bg-brand-lemon/60 px-3 py-1 rounded-full uppercase tracking-wider">Kartu Adopsi Pohon</span>
                                        <h3 class="text-xl font-extrabold text-brand-dark mt-2">Pohon Trembesi Rindang</h3>
                                        <p class="text-xs text-gray-500 font-mono italic">Samanea saman • TR-2026-001</p>
                                    </div>
                                    <div class="w-12 h-12 rounded-2xl bg-emerald-50 border border-emerald-100 flex items-center justify-center">
                                        <svg class="w-6 h-6 text-emerald-700" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                                    </div>
                                </div>

                                <!-- Metric highlight -->
                                <div class="grid grid-cols-2 gap-3 pt-2">
                                    <div class="p-3.5 rounded-2xl bg-brand-surface border border-emerald-50">
                                        <span class="text-[11px] font-semibold text-gray-500">Estimasi Serapan CO₂</span>
                                        <p class="text-lg font-black text-brand-green">28.5 kg <span class="text-xs font-normal text-gray-500">/tahun</span></p>
                                    </div>
                                    <div class="p-3.5 rounded-2xl bg-brand-surface border border-emerald-50">
                                        <span class="text-[11px] font-semibold text-gray-500">Status Perawatan</span>
                                        <p class="text-lg font-black text-emerald-800 flex items-center gap-1.5">
                                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                            Subur
                                        </p>
                                    </div>
                                </div>

                                <!-- QR preview pill -->
                                <div class="p-4 rounded-2xl bg-emerald-900 text-white flex items-center justify-between">
                                    <div class="space-y-0.5">
                                        <p class="text-xs font-bold text-brand-lemon">BioTag QR Active</p>
                                        <p class="text-[11px] text-emerald-200">Pindai di lokasi pohon sekolah</p>
                                    </div>
                                    <div class="px-3 py-1 rounded-lg bg-emerald-800/80 font-mono text-xs text-brand-lemon font-bold border border-emerald-700">
                                        [QR VALID]
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Live Impact Aggregate Stats -->
        <section class="py-10 bg-white border-y border-emerald-100/80">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
                    <div class="space-y-1">
                        <span class="text-3xl sm:text-4xl font-black text-brand-green">{{ stats.total_trees }}</span>
                        <p class="text-xs sm:text-sm font-semibold text-gray-600">Pohon Terdata</p>
                    </div>
                    <div class="space-y-1">
                        <span class="text-3xl sm:text-4xl font-black text-brand-dark">{{ stats.adopted_trees }}</span>
                        <p class="text-xs sm:text-sm font-semibold text-gray-600">Pohon Diadopsi Keluarga</p>
                    </div>
                    <div class="space-y-1">
                        <span class="text-3xl sm:text-4xl font-black text-brand-green">{{ stats.schools_count }}</span>
                        <p class="text-xs sm:text-sm font-semibold text-gray-600">Sekolah Mitra Aktif</p>
                    </div>
                    <div class="space-y-1">
                        <span class="text-3xl sm:text-4xl font-black text-brand-dark">{{ stats.actions_count }}+</span>
                        <p class="text-xs sm:text-sm font-semibold text-gray-600">Katalog Aksi Hijau</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- How It Works (4 Langkah Human-Centred Design) -->
        <section class="py-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto space-y-3 mb-16">
                <span class="text-xs font-extrabold uppercase tracking-widest text-brand-green bg-emerald-50 px-3.5 py-1 rounded-full">Alur Ramah Anak & Keluarga</span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-brand-dark tracking-tight">
                    Bagaimana Lintasan Bekerja?
                </h2>
                <p class="text-sm sm:text-base text-gray-600 leading-relaxed">
                    Sederhana untuk anak, bermanfaat bagi keluarga, dan mudah dikelola oleh guru di sekolah.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <!-- Step 1 -->
                <div class="p-6 rounded-3xl bg-white border border-emerald-100 shadow-sm space-y-4 hover:shadow-md transition-shadow">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-100/70 text-brand-green flex items-center justify-center font-black text-lg">
                        1
                    </div>
                    <h3 class="font-extrabold text-lg text-brand-dark">Daftar & Hubungkan Sekolah</h3>
                    <p class="text-xs text-gray-600 leading-relaxed">
                        Masuk dengan akun, masukkan kode sekolahmu, dan tunggu verifikasi mudah dari guru kelas.
                    </p>
                </div>

                <!-- Step 2 -->
                <div class="p-6 rounded-3xl bg-white border border-emerald-100 shadow-sm space-y-4 hover:shadow-md transition-shadow">
                    <div class="w-12 h-12 rounded-2xl bg-brand-lemon/60 text-brand-dark flex items-center justify-center font-black text-lg">
                        2
                    </div>
                    <h3 class="font-extrabold text-lg text-brand-dark">Pahami Jejak Emisi Rumah</h3>
                    <p class="text-xs text-gray-600 leading-relaxed">
                        Catat penggunaan listrik dan perjalanan harian. Kalkulator ramah anak menyajikan estimasi tanpa istilah rumit.
                    </p>
                </div>

                <!-- Step 3 -->
                <div class="p-6 rounded-3xl bg-white border border-emerald-100 shadow-sm space-y-4 hover:shadow-md transition-shadow">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-100/70 text-brand-green flex items-center justify-center font-black text-lg">
                        3
                    </div>
                    <h3 class="font-extrabold text-lg text-brand-dark">Pindai QR & Adopsi Pohon</h3>
                    <p class="text-xs text-gray-600 leading-relaxed">
                        Temukan kode QR BioTag di pohon sekolah atau taman komunitas. Pindai untuk mengadopsi pohonmu secara digital.
                    </p>
                </div>

                <!-- Step 4 -->
                <div class="p-6 rounded-3xl bg-white border border-emerald-100 shadow-sm space-y-4 hover:shadow-md transition-shadow">
                    <div class="w-12 h-12 rounded-2xl bg-brand-lemon/60 text-brand-dark flex items-center justify-center font-black text-lg">
                        4
                    </div>
                    <h3 class="font-extrabold text-lg text-brand-dark">Aksi Hijau Berkelanjutan</h3>
                    <p class="text-xs text-gray-600 leading-relaxed">
                        Selesaikan aksi kecil seperti mematikan lampu, menyiram pohon, dan saksikan pertumbuhan jejak hijaumu!
                    </p>
                </div>
            </div>
        </section>

        <!-- Featured Trees Carousel/Grid -->
        <section class="py-16 bg-white border-t border-emerald-100/70">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-10 gap-4">
                    <div>
                        <span class="text-xs font-bold text-brand-green uppercase tracking-wider">BioTag Monitored Trees</span>
                        <h2 class="text-2xl sm:text-3xl font-extrabold text-brand-dark mt-1">Pohon dalam Program</h2>
                    </div>
                    <Link :href="route('public.map')" class="text-sm font-bold text-brand-green hover:text-brand-dark flex items-center gap-1">
                        Lihat Seluruh Pohon di Peta &rarr;
                    </Link>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div v-for="tree in featured_trees" :key="tree.id" class="p-5 rounded-3xl bg-brand-surface border border-emerald-100 shadow-xs space-y-4 flex flex-col justify-between">
                        <div class="space-y-2">
                            <div class="flex justify-between items-start">
                                <span class="text-xs font-mono font-bold px-2.5 py-1 rounded-lg bg-emerald-100 text-emerald-800">
                                    {{ tree.tree_code }}
                                </span>
                                <span class="text-xs font-bold px-2 py-0.5 rounded-full" :class="tree.status === 'adopted' ? 'bg-amber-100 text-amber-800' : 'bg-emerald-200 text-emerald-900'">
                                    {{ tree.status === 'adopted' ? 'Diadopsi' : 'Tersedia' }}
                                </span>
                            </div>
                            <h4 class="font-extrabold text-base text-brand-dark">{{ tree.common_name }}</h4>
                            <p class="text-xs text-gray-500 italic">{{ tree.species_name }}</p>
                            <p class="text-xs text-gray-600 line-clamp-2">{{ tree.description }}</p>
                        </div>

                        <div class="pt-3 border-t border-emerald-100 flex items-center justify-between text-xs">
                            <span class="text-gray-500">Serapan CO₂:</span>
                            <span class="font-black text-brand-green">{{ tree.co2_absorption_kg_year }} kg/th</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="py-16 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="rounded-3xl bg-gradient-to-r from-brand-dark via-emerald-900 to-brand-green p-10 sm:p-14 text-white text-center space-y-6 shadow-xl shadow-brand-green/10">
                <h2 class="text-3xl sm:text-4xl font-extrabold tracking-tight">
                    Setiap Langkah Kecil Membawa Perubahan Nyata.
                </h2>
                <p class="text-sm sm:text-base text-emerald-100 max-w-xl mx-auto leading-relaxed">
                    Yuk, bergabung bersama ribuan siswa dan keluarga lainnya untuk menumbuhkan kebiasaan hijau dan merawat masa depan bumi.
                </p>
                <div class="pt-2 flex flex-wrap justify-center gap-4">
                    <Link :href="route('register')" class="px-8 py-3.5 rounded-2xl bg-brand-lemon hover:bg-brand-lemon-hover text-brand-dark font-extrabold text-sm shadow-md transition-transform active:scale-95">
                        Daftar Gratis Sekarang
                    </Link>
                    <Link :href="route('login')" class="px-8 py-3.5 rounded-2xl bg-white/10 hover:bg-white/20 text-white font-semibold text-sm border border-white/20 transition-colors">
                        Sudah Punya Akun? Masuk
                    </Link>
                </div>
            </div>
        </section>
    </PublicLayout>
</template>
