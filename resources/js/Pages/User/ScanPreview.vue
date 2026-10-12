<script setup>
import UserLayout from '@/Layouts/UserLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    token: {
        type: String,
        required: true,
    },
    result: {
        type: Object,
        required: true,
    },
});

const form = useForm({
    token: props.token,
    notes: '',
});

function confirmAdoption() {
    form.post(route('user.adopt-tree'));
}
</script>

<template>
    <Head title="Konfirmasi Adopsi Pohon — BioTag QR" />

    <UserLayout>
        <div class="max-w-xl mx-auto py-8">
            <div class="bg-white rounded-3xl border border-emerald-100 p-8 shadow-xl space-y-6">
                <!-- Back Link -->
                <Link :href="route('user.dashboard')" class="inline-flex items-center gap-1.5 text-xs font-bold text-gray-500 hover:text-brand-dark">
                    <span>&larr;</span> Kembali ke Dashboard
                </Link>

                <!-- Token verification header -->
                <div class="flex items-center gap-3 pb-4 border-b border-gray-100">
                    <div class="w-12 h-12 rounded-2xl bg-brand-lemon/70 text-brand-dark flex items-center justify-center font-bold">
                        <svg class="w-6 h-6 text-brand-dark" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                    </div>
                    <div>
                        <span class="text-xs font-mono font-bold px-2 py-0.5 rounded bg-emerald-100 text-emerald-900">
                            BioTag Token: {{ token }}
                        </span>
                        <h1 class="text-xl font-extrabold text-brand-dark mt-1">Verifikasi Kode QR Pohon</h1>
                    </div>
                </div>

                <!-- Case 1: Successfully Valid & Available -->
                <div v-if="result.success && result.tree" class="space-y-6">
                    <div class="p-4 rounded-2xl bg-emerald-50/80 border border-emerald-200 space-y-1">
                        <span class="text-[11px] font-bold uppercase tracking-wider text-emerald-800">Status Validasi</span>
                        <p class="text-xs text-brand-dark font-medium leading-relaxed">
                            “Pohon ini tersedia untuk diadopsi. Setelah konfirmasi, pohon akan muncul di akunmu.”
                        </p>
                    </div>

                    <!-- Tree Profile Card -->
                    <div class="p-5 rounded-2xl bg-brand-surface border border-emerald-100 space-y-4">
                        <div class="flex justify-between items-start">
                            <div>
                                <span class="text-xs font-mono font-bold text-emerald-800">{{ result.tree.tree_code }}</span>
                                <h3 class="text-xl font-black text-brand-dark">{{ result.tree.common_name }}</h3>
                                <p class="text-xs text-gray-500 italic">{{ result.tree.species_name }}</p>
                            </div>
                            <span class="text-xs font-bold px-2.5 py-1 rounded-full bg-emerald-200 text-emerald-900">
                                Siap Diadopsi
                            </span>
                        </div>

                        <p class="text-xs text-gray-600 leading-relaxed">{{ result.tree.description }}</p>

                        <div class="grid grid-cols-2 gap-3 pt-2 text-xs">
                            <div class="p-3 rounded-xl bg-white border border-emerald-100">
                                <span class="text-gray-500 text-[11px]">Serapan Karbon:</span>
                                <p class="text-base font-extrabold text-brand-green">{{ result.tree.co2_absorption_kg_year }} kg CO₂/th</p>
                            </div>
                            <div class="p-3 rounded-xl bg-white border border-emerald-100">
                                <span class="text-gray-500 text-[11px]">Lokasi Penanaman:</span>
                                <p class="text-xs font-bold text-gray-700 truncate">{{ result.tree.location?.address_description || 'Taman Sekolah' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Adoption Form -->
                    <form @submit.prevent="confirmAdoption" class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Catatan Komitmen Perawatan (Opsional)</label>
                            <input
                                v-model="form.notes"
                                type="text"
                                placeholder="Contoh: Diadopsi oleh Budi dan keluarga untuk dirawat setiap pekan."
                                class="w-full px-4 py-2.5 rounded-2xl border border-gray-200 text-xs focus:ring-2 focus:ring-brand-green/30 focus:border-brand-green"
                            />
                        </div>

                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="w-full py-3.5 rounded-2xl bg-brand-lemon hover:bg-brand-lemon-hover text-brand-dark font-extrabold text-sm shadow-md transition-transform active:scale-95"
                        >
                            Konfirmasi & Adopsi Pohon Sekarang
                        </button>
                    </form>
                </div>

                <!-- Case 2: Tree is already adopted or invalid token -->
                <div v-else class="space-y-5 text-center py-6">
                    <div class="w-14 h-14 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center mx-auto">
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    </div>
                    <h3 class="text-base font-bold text-gray-800">Pohon Tidak Dapat Diadopsi</h3>
                    <p class="text-xs text-red-600 font-medium bg-red-50 p-3 rounded-xl border border-red-200">
                        {{ result.message }}
                    </p>
                    <div>
                        <Link :href="route('user.dashboard')" class="inline-flex px-5 py-2.5 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold text-xs">
                            Kembali ke Dashboard
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </UserLayout>
</template>
