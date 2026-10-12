<script setup>
import OfficerLayout from '@/Layouts/OfficerLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    trees: {
        type: Array,
        default: () => [],
    },
    recentObservations: {
        type: Array,
        default: () => [],
    },
    stats: {
        type: Object,
        default: () => ({ total_trees: 0, monitored_trees: 0, adopted_trees: 0, total_inspections: 0 }),
    },
});

const form = useForm({
    tree_id: props.trees[0]?.id || '',
    condition_status: 'thriving',
    notes: '',
    photo: null,
    photo_url: '/images/tree-sample-1.jpg',
});

const previewImage = ref('/images/tree-sample-1.jpg');

function onFileChange(e) {
    const file = e.target.files[0];
    if (file) {
        form.photo = file;
        previewImage.value = URL.createObjectURL(file);
    }
}

function submitObservation() {
    form.post(route('officer.store-observation'), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset('notes');
        },
    });
}
</script>

<template>
    <Head title="Pengawas Lapangan — Inspeksi & Update Foto Pohon" />

    <OfficerLayout>
        <div class="space-y-8">
            <!-- Header Section -->
            <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-emerald-800 bg-emerald-50 px-3 py-1 rounded-full">
                        Petugas Lapangan BioTag × Lintasan
                    </span>
                    <h1 class="text-2xl sm:text-3xl font-black text-slate-900 mt-2">
                        Pencatatan Inspeksi & Kirim Foto Pohon
                    </h1>
                    <p class="text-xs text-slate-500 mt-1">
                        Foto dan catatan inspeksi yang Anda kirim akan langsung tampil di dashboard anak/keluarga yang mengadopsi pohon.
                    </p>
                </div>

                <div class="grid grid-cols-3 gap-3 bg-slate-50 p-3 rounded-2xl border border-slate-100 text-center shrink-0">
                    <div class="px-3">
                        <span class="block text-xl font-black text-slate-900">{{ stats.total_trees }}</span>
                        <span class="text-[10px] text-slate-500 font-bold uppercase">Total Pohon</span>
                    </div>
                    <div class="px-3 border-x border-slate-200">
                        <span class="block text-xl font-black text-emerald-600">{{ stats.adopted_trees }}</span>
                        <span class="text-[10px] text-slate-500 font-bold uppercase">Diadopsi Anak</span>
                    </div>
                    <div class="px-3">
                        <span class="block text-xl font-black text-blue-600">{{ stats.total_inspections }}</span>
                        <span class="text-[10px] text-slate-500 font-bold uppercase">Total Laporan</span>
                    </div>
                </div>
            </div>

            <!-- Main Content: Form & Recent Feed -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                <!-- Inspection Submission Form -->
                <div class="lg:col-span-5 bg-white rounded-3xl border border-slate-200 p-6 shadow-sm space-y-5">
                    <div class="border-b border-slate-100 pb-3">
                        <h3 class="font-extrabold text-lg text-slate-900">Form Laporan & Foto Pohon</h3>
                        <p class="text-xs text-slate-500">Pilih pohon atau scan token QR untuk mengirim kondisi terkini</p>
                    </div>

                    <form @submit.prevent="submitObservation" class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Pilih Pohon yang Diinspeksi</label>
                            <select
                                v-model="form.tree_id"
                                class="w-full px-3.5 py-2.5 rounded-2xl border border-slate-200 text-xs focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 bg-white"
                                required
                            >
                                <option v-for="t in trees" :key="t.id" :value="t.id">
                                    {{ t.tree_code }} — {{ t.common_name }} ({{ t.status === 'adopted' ? 'Adopter: ' + (t.current_adoption?.user?.name || 'Siswa') : 'Tersedia' }})
                                </option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Kondisi Kesehatan Pohon</label>
                            <div class="grid grid-cols-2 gap-2">
                                <label class="flex items-center gap-2 p-2.5 rounded-xl border cursor-pointer transition-colors text-xs font-semibold" :class="form.condition_status === 'thriving' ? 'bg-emerald-50 border-emerald-500 text-emerald-900' : 'border-slate-200 text-slate-600'">
                                    <input type="radio" v-model="form.condition_status" value="thriving" class="sr-only" />
                                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                                    Sangat Subur
                                </label>
                                <label class="flex items-center gap-2 p-2.5 rounded-xl border cursor-pointer transition-colors text-xs font-semibold" :class="form.condition_status === 'healthy' ? 'bg-blue-50 border-blue-500 text-blue-900' : 'border-slate-200 text-slate-600'">
                                    <input type="radio" v-model="form.condition_status" value="healthy" class="sr-only" />
                                    <span class="w-2.5 h-2.5 rounded-full bg-blue-500"></span>
                                    Sehat Normal
                                </label>
                                <label class="flex items-center gap-2 p-2.5 rounded-xl border cursor-pointer transition-colors text-xs font-semibold" :class="form.condition_status === 'needs_care' ? 'bg-amber-50 border-amber-500 text-amber-900' : 'border-slate-200 text-slate-600'">
                                    <input type="radio" v-model="form.condition_status" value="needs_care" class="sr-only" />
                                    <span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span>
                                    Perlu Perawatan
                                </label>
                                <label class="flex items-center gap-2 p-2.5 rounded-xl border cursor-pointer transition-colors text-xs font-semibold" :class="form.condition_status === 'withered' ? 'bg-red-50 border-red-500 text-red-900' : 'border-slate-200 text-slate-600'">
                                    <input type="radio" v-model="form.condition_status" value="withered" class="sr-only" />
                                    <span class="w-2.5 h-2.5 rounded-full bg-red-500"></span>
                                    Layu / Sakit
                                </label>
                            </div>
                        </div>

                        <!-- Photo Upload & Live Preview -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Foto Update Pohon</label>
                            <div class="space-y-3">
                                <div class="relative rounded-2xl overflow-hidden border border-slate-200 bg-slate-50 h-44 flex items-center justify-center">
                                    <img :src="previewImage" alt="Preview Pohon" class="w-full h-full object-cover" />
                                    <span class="absolute bottom-2 left-2 bg-slate-900/80 backdrop-blur-xs text-white text-[10px] font-mono px-2 py-0.5 rounded">
                                        Foto Laporan Siap Kirim
                                    </span>
                                </div>

                                <div class="flex items-center gap-2">
                                    <input
                                        type="file"
                                        accept="image/*"
                                        @change="onFileChange"
                                        class="block w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-emerald-50 file:text-emerald-800 hover:file:bg-emerald-100"
                                    />
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Catatan Hasil Observasi</label>
                            <textarea
                                v-model="form.notes"
                                rows="3"
                                placeholder="Contoh: Pohon disiram pagi ini, pucuk daun baru mekar, penopang kayu kokoh."
                                class="w-full px-3.5 py-2.5 rounded-2xl border border-slate-200 text-xs focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500"
                                required
                            ></textarea>
                        </div>

                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="w-full py-3.5 rounded-2xl bg-emerald-700 hover:bg-emerald-800 text-white font-extrabold text-xs shadow-md transition-transform active:scale-95 flex items-center justify-center gap-2"
                        >
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                            Kirim Laporan & Update Foto untuk Anak
                        </button>
                    </form>
                </div>

                <!-- Recent Inspections Timeline Feed -->
                <div class="lg:col-span-7 bg-white rounded-3xl border border-slate-200 p-6 shadow-sm space-y-4">
                    <div class="flex justify-between items-center pb-2 border-b border-slate-100">
                        <div>
                            <h3 class="font-extrabold text-lg text-slate-900">Riwayat Update Foto Lapangan</h3>
                            <p class="text-xs text-slate-500">Laporan yang telah terkirim dan tampil di akun anak/keluarga</p>
                        </div>
                    </div>

                    <div class="space-y-4 max-h-[640px] overflow-y-auto pr-1">
                        <div
                            v-for="obs in recentObservations"
                            :key="obs.id"
                            class="p-4 rounded-2xl bg-slate-50 border border-slate-100 space-y-3"
                        >
                            <div class="flex justify-between items-start">
                                <div>
                                    <span class="text-[10px] font-mono font-bold px-2 py-0.5 rounded bg-emerald-100 text-emerald-900">
                                        {{ obs.tree?.tree_code }}
                                    </span>
                                    <h4 class="font-bold text-sm text-slate-900 mt-1">{{ obs.tree?.common_name }}</h4>
                                    <p class="text-[11px] text-slate-500">Petugas: {{ obs.observer?.name || 'Pengawas Lapangan' }}</p>
                                </div>
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold" :class="obs.condition_status === 'thriving' ? 'bg-emerald-100 text-emerald-800' : obs.condition_status === 'healthy' ? 'bg-blue-100 text-blue-800' : 'bg-amber-100 text-amber-800'">
                                    {{ obs.condition_status === 'thriving' ? 'Sangat Subur' : obs.condition_status === 'healthy' ? 'Sehat' : 'Perlu Perawatan' }}
                                </span>
                            </div>

                            <div v-if="obs.photo_url" class="rounded-xl overflow-hidden border border-slate-200 max-h-48 w-full bg-slate-100">
                                <img :src="obs.photo_url" alt="Foto Observasi" class="w-full h-44 object-cover" />
                            </div>

                            <p class="text-xs text-slate-700 leading-relaxed font-normal bg-white p-3 rounded-xl border border-slate-100">
                                “{{ obs.notes }}”
                            </p>

                            <div class="flex justify-between items-center text-[10px] text-slate-400 font-mono">
                                <span>Waktu: {{ obs.observed_at ? new Date(obs.observed_at).toLocaleDateString('id-ID', { year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit' }) : '-' }}</span>
                                <span class="text-emerald-700 font-semibold font-sans">✓ Terhubung ke Akun Anak</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </OfficerLayout>
</template>
