<script setup>
import PublicLayout from '@/Layouts/PublicLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Tentang Lintasan & BioTag — Kolaborasi & Metodologi" />

    <PublicLayout>
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-12">
            <!-- Header -->
            <div class="space-y-3">
                <span class="text-xs font-bold uppercase tracking-widest text-brand-green bg-emerald-50 px-3.5 py-1 rounded-full">Tentang & Transparansi</span>
                <h1 class="text-3xl sm:text-4xl font-black text-brand-dark tracking-tight">
                    Kolaborasi Yayasan Lintasan × BioTag
                </h1>
                <p class="text-base text-gray-600 leading-relaxed max-w-3xl">
                    Lintasan — Jejak Hijau Keluargaku dikembangkan melalui sinergi program The Community Space bersama Yota Adiwidya Center sebagai penyedia teknologi BioTag.
                </p>
            </div>

            <!-- Role Breakdown Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Lintasan Card -->
                <div class="p-8 rounded-3xl bg-white border border-emerald-100 shadow-sm space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-2xl bg-brand-green text-white flex items-center justify-center font-black text-lg">
                            YL
                        </div>
                        <div>
                            <h3 class="font-black text-xl text-brand-dark">Yayasan Lintasan</h3>
                            <p class="text-xs text-brand-green font-bold">Mitra Pelaksana & Brand Utama</p>
                        </div>
                    </div>
                    <p class="text-xs text-gray-600 leading-relaxed">
                        Lintasan menjadi wajah publik yang berinteraksi langsung dengan sekolah, siswa, dan komunitas keluarga. Mengelola kurikulum edukasi lingkungan, kemitraan sekolah, koordinasi penanaman pohon, dan kampanye aksi hijau di lapangan.
                    </p>
                </div>

                <!-- BioTag Card -->
                <div class="p-8 rounded-3xl bg-white border border-emerald-100 shadow-sm space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-2xl bg-brand-lemon text-brand-dark flex items-center justify-center font-black text-lg">
                            BT
                        </div>
                        <div>
                            <h3 class="font-black text-xl text-brand-dark">BioTag</h3>
                            <p class="text-xs text-emerald-800 font-bold">Yota Adiwidya Center • Technology Platform</p>
                        </div>
                    </div>
                    <p class="text-xs text-gray-600 leading-relaxed">
                        BioTag menyediakan fondasi teknologi: mesin pencatatan pohon, enkripsi token QR aman, pemantauan status pohon, audit log teknis, serta infrastruktur data teragregasi yang menjaga keutuhan platform.
                    </p>
                </div>
            </div>

            <!-- Transparency & Methodology Section -->
            <div class="p-8 rounded-3xl bg-white border border-emerald-100 shadow-sm space-y-6">
                <div class="border-b border-gray-100 pb-4">
                    <h3 class="text-xl font-extrabold text-brand-dark">Transparansi Metodologi Perhitungan Karbon</h3>
                    <p class="text-xs text-gray-500 mt-1">Standar Ilmiah dan Batasan Klaim Edukasi</p>
                </div>

                <div class="space-y-4 text-xs text-gray-600 leading-relaxed">
                    <p>
                        <strong class="text-brand-dark">Alat Edukasi, Bukan Audit Tersertifikasi:</strong> Kalkulator Jejak Hijau Keluargaku dirancang sebagai instrumen edukasi keluarga untuk membangun kesadaran emisi rumah tangga, bukan sebagai sertifikasi audit karbon resmi untuk perdagangan kredit karbon.
                    </p>
                    <p>
                        <strong class="text-brand-dark">Faktor Emisi Berversi (v1.0-2026):</strong> Rumus perhitungan menggunakan formula konseptual <span class="font-mono bg-gray-100 px-1 py-0.5 rounded">Emisi = Data Aktivitas × Faktor Emisi</span> dengan referensi resmi Kementerian ESDM (faktor jaringan listrik Jamali 0.785 kg CO₂e/kWh) dan pedoman inventarisasi GRK nasional KLHK/IPCC 2026.
                    </p>
                    <p>
                        <strong class="text-brand-dark">Daya Serap Pohon:</strong> Angka estimasi serapan karbon pohon (18 - 28 kg/tahun) merupakan rata-rata biologis tahunan spesies pohon dewasa di iklim tropis. Adopsi digital pohon tidak otomatis mengimbangi 100% emisi keluarga tanpa verifikasi usia, kondisi hidup, dan pemantauan berkala pohon tersebut.
                    </p>
                </div>
            </div>

            <!-- Child Privacy & Ethics Section -->
            <div class="p-8 rounded-3xl bg-emerald-950 text-white shadow-lg space-y-5">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-brand-lemon" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    <h3 class="text-lg font-bold text-brand-lemon">Prinsip Perlindungan Data & Etika Anak</h3>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-xs text-emerald-100">
                    <div class="space-y-1.5">
                        <h4 class="font-bold text-white text-sm">Privasi Koordinat</h4>
                        <p class="leading-relaxed text-emerald-200">
                            Peta publik mengaburkan koordinat presisi pohon dan lingkungan sekolah ke radius aman (~100m) untuk mencegah pembocoran lokasi anak.
                        </p>
                    </div>
                    <div class="space-y-1.5">
                        <h4 class="font-bold text-white text-sm">Tanpa Leaderboard Publik</h4>
                        <p class="leading-relaxed text-emerald-200">
                            Tidak ada papan peringkat emisi publik yang mempermalukan keluarga dengan skor emisi tinggi. Fokus platform adalah kemajuan aksi kebiasaan baik.
                        </p>
                    </div>
                    <div class="space-y-1.5">
                        <h4 class="font-bold text-white text-sm">Verifikasi Guru & Wali</h4>
                        <p class="leading-relaxed text-emerald-200">
                            Setiap akun siswa diverifikasi oleh guru sekolah mitra untuk memastikan lingkungan belajar yang aman dan terpercaya.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </PublicLayout>
</template>
