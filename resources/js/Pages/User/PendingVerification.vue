<script setup>
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    membership: {
        type: Object,
        default: null,
    },
    availableSchools: {
        type: Array,
        default: () => [],
    },
});

const page = usePage();
const flash = computed(() => page.props.flash || {});

const form = useForm({
    school_code: '',
});

function submitSchool() {
    form.post(route('user.submit-school-code'), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
        },
    });
}
</script>

<template>
    <Head title="Menunggu Verifikasi Sekolah — Lintasan" />

    <div class="min-h-screen bg-brand-surface font-sans text-brand-forest flex flex-col justify-center items-center px-4 py-12 selection:bg-brand-lemon selection:text-brand-dark">
        <!-- Flash Toast -->
        <div v-if="flash.success || flash.error" class="fixed top-5 right-5 z-50 max-w-sm rounded-2xl shadow-lg border p-4 bg-white" :class="flash.success ? 'border-brand-green/30 text-brand-dark' : 'border-red-300 text-red-900'">
            <div class="flex items-center gap-2 text-sm font-semibold">
                <span>{{ flash.success ? '✓' : '!' }}</span>
                <span>{{ flash.success || flash.error }}</span>
            </div>
        </div>

        <div class="max-w-md w-full bg-white rounded-3xl p-8 border border-emerald-100 shadow-xl space-y-6 text-center">
            <!-- Icon & Header -->
            <div class="w-16 h-16 rounded-3xl bg-amber-100 text-amber-800 flex items-center justify-center text-3xl mx-auto shadow-inner">
                ⏳
            </div>

            <div class="space-y-2">
                <span class="text-xs font-bold uppercase tracking-wider text-amber-800 bg-amber-50 px-3 py-1 rounded-full">
                    Status Pendaftaran
                </span>
                <h1 class="text-2xl font-black text-brand-dark tracking-tight">
                    Menunggu Verifikasi Sekolah
                </h1>
                <p class="text-xs text-gray-600 leading-relaxed">
                    “Akunmu sedang menunggu verifikasi sekolah. Guru akan membantu mengaktifkannya agar kamu bisa mulai mencatat aksi hijau dan mengadopsi pohon.”
                </p>
            </div>

            <!-- Current School Status if already linked -->
            <div v-if="membership" class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-left space-y-1">
                <p class="text-[11px] font-bold text-gray-500 uppercase">Sekolah Terhubung:</p>
                <p class="text-sm font-extrabold text-brand-dark">{{ membership.school.name }}</p>
                <p class="text-xs text-emerald-800">Status: <span class="font-bold">Menunggu persetujuan guru kelas</span></p>
            </div>

            <!-- Form: Link or Change School Code -->
            <div class="pt-2 border-t border-gray-100 space-y-4 text-left">
                <h3 class="text-xs font-bold text-gray-700">Masukkan / Ganti Kode Sekolah Mitra</h3>
                <form @submit.prevent="submitSchool" class="space-y-3">
                    <input
                        v-model="form.school_code"
                        type="text"
                        placeholder="Contoh: SCH-JKT-01"
                        class="w-full px-4 py-2.5 rounded-2xl border border-gray-200 text-xs sm:text-sm focus:ring-2 focus:ring-brand-green/30 focus:border-brand-green uppercase font-mono"
                        required
                    />

                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="w-full py-2.5 rounded-2xl bg-brand-green hover:bg-brand-dark text-white font-bold text-xs shadow-sm transition-all"
                    >
                        Hubungkan ke Sekolah
                    </button>
                </form>

                <div class="text-[11px] text-gray-500 space-y-1">
                    <p class="font-bold text-gray-700">Daftar Kode Sekolah Uji Coba:</p>
                    <ul class="space-y-0.5">
                        <li v-for="sch in availableSchools" :key="sch.id">
                            • <span class="font-mono font-bold text-emerald-800">{{ sch.code }}</span>: {{ sch.name }} ({{ sch.city }})
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Logout Link -->
            <div class="pt-4 border-t border-gray-100 flex justify-between items-center text-xs">
                <Link :href="route('home')" class="text-gray-500 hover:text-brand-dark">Kembali ke Beranda</Link>
                <Link :href="route('logout')" method="post" as="button" class="font-bold text-red-600 hover:text-red-800">
                    Keluar (Logout)
                </Link>
            </div>
        </div>
    </div>
</template>
