<script setup>
import PublicLayout from '@/Layouts/PublicLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { ref } from 'vue';

const selectedTopic = ref('trees');

const topics = [
    { id: 'trees', title: 'Pohon & Serapan Karbon', color: 'bg-emerald-50 text-emerald-800' },
    { id: 'electricity', title: 'Listrik & Hemat Energi', color: 'bg-amber-50 text-amber-800' },
    { id: 'travel', title: 'Perjalanan Rendah Emisi', color: 'bg-blue-50 text-blue-800' },
    { id: 'waste', title: 'Sampah & Pilah Plastik', color: 'bg-purple-50 text-purple-800' },
];
</script>

<template>
    <Head title="Pusat Edukasi Keluarga — Lintasan" />

    <PublicLayout>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-10">
            <!-- Header -->
            <div class="text-center max-w-2xl mx-auto space-y-3">
                <span class="text-xs font-bold uppercase tracking-widest text-brand-green bg-emerald-50 px-3.5 py-1 rounded-full">Belajar Bersama Keluarga</span>
                <h1 class="text-3xl sm:text-4xl font-black text-brand-dark tracking-tight">Pusat Edukasi Lingkungan</h1>
                <p class="text-sm text-gray-600 leading-relaxed">
                    Materi singkat, praktis, dan ramah anak untuk memahami bagaimana tindakan sehari-hari kita berdampak bagi kelestarian alam.
                </p>
            </div>

            <!-- Topic Switcher -->
            <div class="flex flex-wrap justify-center gap-3">
                <button
                    v-for="topic in topics"
                    :key="topic.id"
                    @click="selectedTopic = topic.id"
                    class="flex items-center gap-2 px-5 py-2.5 rounded-2xl text-xs sm:text-sm font-bold transition-all"
                    :class="selectedTopic === topic.id ? 'bg-brand-green text-white shadow-md shadow-brand-green/20 scale-105' : 'bg-white border border-gray-200 text-gray-700 hover:bg-emerald-50'"
                >
                    <svg v-if="topic.id === 'trees'" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                    <svg v-else-if="topic.id === 'electricity'" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    <svg v-else-if="topic.id === 'travel'" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                    <svg v-else class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    <span>{{ topic.title }}</span>
                </button>
            </div>

            <!-- Topic 1: Pohon & Serapan Karbon -->
            <div v-show="selectedTopic === 'trees'" class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="p-6 rounded-3xl bg-white border border-emerald-100 shadow-sm space-y-3">
                    <div class="w-10 h-10 rounded-2xl bg-emerald-50 text-emerald-700 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                    </div>
                    <h3 class="font-extrabold text-base text-brand-dark">Bagaimana Pohon Menyerap Karbon?</h3>
                    <p class="text-xs text-gray-600 leading-relaxed">
                        Pohon bernapas melalui fotosintesis. Daun menyerap gas Karbon Dioksida (CO₂) dari udara, menguncinya menjadi kayu dan daun, lalu melepaskan Oksigen (O₂) segar untuk kita hirup!
                    </p>
                </div>
                <div class="p-6 rounded-3xl bg-white border border-emerald-100 shadow-sm space-y-3">
                    <div class="w-10 h-10 rounded-2xl bg-emerald-50 text-emerald-700 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                    </div>
                    <h3 class="font-extrabold text-base text-brand-dark">Pohon Peneduh vs Pohon Buah</h3>
                    <p class="text-xs text-gray-600 leading-relaxed">
                        Pohon peneduh seperti Trembesi memiliki tajuk besar dengan daya serap karbon hingga 28 kg/tahun, sedangkan pohon buah seperti Mangga memberi nutrisi sekaligus keteduhan pekarangan.
                    </p>
                </div>
                <div class="p-6 rounded-3xl bg-white border border-emerald-100 shadow-sm space-y-3">
                    <div class="w-10 h-10 rounded-2xl bg-emerald-50 text-emerald-700 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                    </div>
                    <h3 class="font-extrabold text-base text-brand-dark">Merawat Pohon Adopsimu</h3>
                    <p class="text-xs text-gray-600 leading-relaxed">
                        Pohon muda membutuhkan air teratur saat musim kemarau, pembersihan gulma di sekitar akar, dan tanah yang gembur agar akarnya dapat bernapas dengan baik.
                    </p>
                </div>
            </div>

            <!-- Topic 2: Listrik & Energi -->
            <div v-show="selectedTopic === 'electricity'" class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="p-6 rounded-3xl bg-white border border-amber-100 shadow-sm space-y-3">
                    <div class="w-10 h-10 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    </div>
                    <h3 class="font-extrabold text-base text-brand-dark">Cahaya Matahari vs Lampu</h3>
                    <p class="text-xs text-gray-600 leading-relaxed">
                        Buka tirai jendela di pagi hari! Menggunakan cahaya alami menghemat 1-2 kWh listrik per hari dan membuat ruangan lebih segar dan bebas bakteri.
                    </p>
                </div>
                <div class="p-6 rounded-3xl bg-white border border-amber-100 shadow-sm space-y-3">
                    <div class="w-10 h-10 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                    <h3 class="font-extrabold text-base text-brand-dark">Bahaya Daya Vampir (Standby)</h3>
                    <p class="text-xs text-gray-600 leading-relaxed">
                        Charger HP atau laptop yang dicolokkan tanpa mengisi baterai tetap menyedot listrik kecil terus menerus. Ingatkan keluarga untuk selalu mencabut colokannya ya!
                    </p>
                </div>
                <div class="p-6 rounded-3xl bg-white border border-amber-100 shadow-sm space-y-3">
                    <div class="w-10 h-10 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <h3 class="font-extrabold text-base text-brand-dark">Atur Suhu AC yang Nyaman</h3>
                    <p class="text-xs text-gray-600 leading-relaxed">
                        Atur suhu pendingin ruangan di 24°C - 25°C. Setiap penurunan 1°C di bawah 24°C meningkatkan pemakaian energi hingga 6%.
                    </p>
                </div>
            </div>

            <!-- Topic 3: Perjalanan -->
            <div v-show="selectedTopic === 'travel'" class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="p-6 rounded-3xl bg-white border border-blue-100 shadow-sm space-y-3">
                    <div class="w-10 h-10 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7a4 4 0 11-8 0 4 4 0 018 0zM9 14a6 6 0 00-6 6v1h12v-1a6 6 0 00-6-6z"/></svg>
                    </div>
                    <h3 class="font-extrabold text-base text-brand-dark">Jalan Kaki untuk Jarak Dekat</h3>
                    <p class="text-xs text-gray-600 leading-relaxed">
                        Perjalanan kurang dari 500 meter ke warung atau sekolah teman paling asyik ditempuh dengan berjalan kaki. Sehat untuk tubuh dan nol emisi karbon!
                    </p>
                </div>
                <div class="p-6 rounded-3xl bg-white border border-blue-100 shadow-sm space-y-3">
                    <div class="w-10 h-10 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                    </div>
                    <h3 class="font-extrabold text-base text-brand-dark">Bersepeda Bersama Teman</h3>
                    <p class="text-xs text-gray-600 leading-relaxed">
                        Mengendarai sepeda adalah cara menyenangkan mengurangi kemacetan dan polusi udara di sekitar lingkungan tempat tinggal dan sekolah.
                    </p>
                </div>
                <div class="p-6 rounded-3xl bg-white border border-blue-100 shadow-sm space-y-3">
                    <div class="w-10 h-10 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                    </div>
                    <h3 class="font-extrabold text-base text-brand-dark">Petualangan Angkutan Umum</h3>
                    <p class="text-xs text-gray-600 leading-relaxed">
                        Satu bus Transjakarta atau gerbong MRT dapat mengangkut puluhan orang sekaligus, menghemat ratusan kilogram emisi dibandingkan mobil pribadi.
                    </p>
                </div>
            </div>

            <!-- Topic 4: Sampah & Daur Ulang -->
            <div v-show="selectedTopic === 'waste'" class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="p-6 rounded-3xl bg-white border border-purple-100 shadow-sm space-y-3">
                    <div class="w-10 h-10 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                    </div>
                    <h3 class="font-extrabold text-base text-brand-dark">Pahlawan Tumbler</h3>
                    <p class="text-xs text-gray-600 leading-relaxed">
                        Membawa botol minum sendiri mencegah ratusan botol plastik sekali pakai berakhir di tempat pembuangan sampah atau sungai.
                    </p>
                </div>
                <div class="p-6 rounded-3xl bg-white border border-purple-100 shadow-sm space-y-3">
                    <div class="w-10 h-10 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <h3 class="font-extrabold text-base text-brand-dark">Habiskan Makanan di Piringmu</h3>
                    <p class="text-xs text-gray-600 leading-relaxed">
                        Sisa makanan yang membusuk di TPA menghasilkan gas metana yang sangat kuat memanaskan bumi. Ambil porsi yang pas dan habiskan makananmu!
                    </p>
                </div>
                <div class="p-6 rounded-3xl bg-white border border-purple-100 shadow-sm space-y-3">
                    <div class="w-10 h-10 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    </div>
                    <h3 class="font-extrabold text-base text-brand-dark">Pilah Sebelum Buang</h3>
                    <p class="text-xs text-gray-600 leading-relaxed">
                        Pisahkan sampah kering (kertas, kardus, botol plastik) agar pemulung dan bank sampah dapat mendaur ulangnya menjadi barang baru yang bermanfaat.
                    </p>
                </div>
            </div>

            <!-- Bottom CTA -->
            <div class="p-8 rounded-3xl bg-emerald-50 border border-emerald-200 text-center space-y-4">
                <h3 class="font-extrabold text-xl text-brand-dark">Siap Mempraktekkan Aksi Hijau Hari Ini?</h3>
                <p class="text-xs text-gray-600 max-w-md mx-auto">
                    Buka dashboard keluargamu untuk mencatat aksi kecil hari ini dan kumpulkan poin dampak positif!
                </p>
                <div>
                    <Link :href="route('user.dashboard')" class="inline-flex px-6 py-3 rounded-2xl bg-brand-green hover:bg-brand-dark text-white font-bold text-xs shadow-md transition-all">
                        Buka Dashboard Aksi
                    </Link>
                </div>
            </div>
        </div>
    </PublicLayout>
</template>
