<script setup>
import LintasanLayout from '@/Layouts/LintasanLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps({
    schools: {
        type: Array,
        default: () => [],
    },
    trees: {
        type: Array,
        default: () => [],
    },
    stats: {
        type: Object,
        default: () => ({ total_schools: 0, total_trees: 0, adopted_trees: 0, available_trees: 0 }),
    },
});

const treeForm = useForm({
    tree_code: 'TR-2026-00' + (props.trees.length + 1),
    species_name: '',
    common_name: '',
    description: '',
    co2_absorption_kg_year: '22.50',
    address_description: 'Taman Sekolah Mitra Lintasan',
    latitude: -6.195000,
    longitude: 106.832000,
});

function createTree() {
    treeForm.post(route('lintasan.store-tree'), {
        preserveScroll: true,
        onSuccess: () => {
            treeForm.reset('species_name', 'common_name', 'description');
            treeForm.tree_code = 'TR-2026-00' + (props.trees.length + 2);
        },
    });
}
</script>

<template>
    <Head title="Admin Yayasan Lintasan — Dashboard Program" />

    <LintasanLayout>
        <div class="space-y-8">
            <!-- Header & Program Metrics -->
            <div class="bg-white rounded-3xl p-6 sm:p-8 border border-emerald-100 shadow-sm flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-emerald-800 bg-brand-lemon/60 px-3 py-1 rounded-full">
                        Yayasan Lintasan • The Community Space
                    </span>
                    <h1 class="text-2xl sm:text-3xl font-black text-brand-dark mt-2">
                        Pengelolaan Program & Pohon Komunitas
                    </h1>
                    <p class="text-xs text-gray-500 mt-1">Platform pencatatan pohon berbasis BioTag dan kemitraan sekolah hijau.</p>
                </div>

                <!-- Stats Pill -->
                <div class="grid grid-cols-3 gap-3 bg-brand-surface p-3 rounded-2xl border border-emerald-50 text-center shrink-0">
                    <div class="px-3">
                        <span class="block text-xl font-black text-brand-green">{{ stats.total_trees }}</span>
                        <span class="text-[10px] text-gray-500 font-bold uppercase">Total Pohon</span>
                    </div>
                    <div class="px-3 border-x border-emerald-100">
                        <span class="block text-xl font-black text-emerald-900">{{ stats.adopted_trees }}</span>
                        <span class="text-[10px] text-gray-500 font-bold uppercase">Diadopsi</span>
                    </div>
                    <div class="px-3">
                        <span class="block text-xl font-black text-brand-dark">{{ stats.total_schools }}</span>
                        <span class="text-[10px] text-gray-500 font-bold uppercase">Sekolah Mitra</span>
                    </div>
                </div>
            </div>

            <!-- Content Grid: Register Tree & Trees Catalog -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                <!-- Register New Tree Form -->
                <div class="lg:col-span-4 bg-white rounded-3xl border border-emerald-100 p-6 shadow-sm space-y-4">
                    <div class="border-b border-gray-100 pb-3">
                        <h3 class="font-extrabold text-base text-brand-dark">Daftarkan Pohon Baru</h3>
                        <p class="text-[11px] text-gray-500">Sistem akan otomatis menerbitkan kode QR BioTag aman</p>
                    </div>

                    <form @submit.prevent="createTree" class="space-y-3">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Kode Unik Pohon</label>
                            <input
                                v-model="treeForm.tree_code"
                                type="text"
                                class="w-full px-3.5 py-2.5 rounded-2xl border border-gray-200 text-xs font-mono font-bold focus:ring-2 focus:ring-brand-green/30 focus:border-brand-green uppercase"
                                required
                            />
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Nama Lokal Pohon</label>
                            <input
                                v-model="treeForm.common_name"
                                type="text"
                                placeholder="Contoh: Pohon Jambu Kristal"
                                class="w-full px-3.5 py-2.5 rounded-2xl border border-gray-200 text-xs focus:ring-2 focus:ring-brand-green/30 focus:border-brand-green"
                                required
                            />
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Nama Ilmiah (Spesies)</label>
                            <input
                                v-model="treeForm.species_name"
                                type="text"
                                placeholder="Contoh: Psidium guajava"
                                class="w-full px-3.5 py-2.5 rounded-2xl border border-gray-200 text-xs focus:ring-2 focus:ring-brand-green/30 focus:border-brand-green italic"
                                required
                            />
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Deskripsi Pohon</label>
                            <textarea
                                v-model="treeForm.description"
                                rows="2"
                                placeholder="Informasi singkat pohon..."
                                class="w-full px-3.5 py-2.5 rounded-2xl border border-gray-200 text-xs focus:ring-2 focus:ring-brand-green/30 focus:border-brand-green"
                            ></textarea>
                        </div>

                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <label class="block text-[11px] font-bold text-gray-700 mb-1">Serapan CO₂ (kg/th)</label>
                                <input v-model="treeForm.co2_absorption_kg_year" type="number" step="0.1" class="w-full px-3 py-2 rounded-xl border border-gray-200 text-xs" required />
                            </div>
                            <div>
                                <label class="block text-[11px] font-bold text-gray-700 mb-1">Lokasi Deskriptif</label>
                                <input v-model="treeForm.address_description" type="text" class="w-full px-3 py-2 rounded-xl border border-gray-200 text-xs" />
                            </div>
                        </div>

                        <button
                            type="submit"
                            :disabled="treeForm.processing"
                            class="w-full py-3 rounded-2xl bg-brand-green hover:bg-brand-dark text-white font-extrabold text-xs shadow-sm transition-all"
                        >
                            Simpan & Terbitkan QR BioTag
                        </button>
                    </form>
                </div>

                <!-- Trees Catalog Table -->
                <div class="lg:col-span-8 bg-white rounded-3xl border border-emerald-100 p-6 shadow-sm space-y-4">
                    <div class="flex justify-between items-center pb-2 border-b border-gray-100">
                        <div>
                            <h3 class="font-extrabold text-lg text-brand-dark">Katalog Pohon Program</h3>
                            <p class="text-xs text-gray-500">Daftar pohon, status adopsi, dan token QR BioTag</p>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs">
                            <thead class="bg-gray-50 text-gray-600 uppercase font-bold text-[10px] border-b border-gray-200">
                                <tr>
                                    <th class="p-3">Kode & Spesies</th>
                                    <th class="p-3">Serapan CO₂</th>
                                    <th class="p-3">Status</th>
                                    <th class="p-3">Token QR BioTag</th>
                                    <th class="p-3">Adopter</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <tr v-for="tree in trees" :key="tree.id" class="hover:bg-emerald-50/40 transition-colors">
                                    <td class="p-3">
                                        <p class="font-bold text-brand-dark">{{ tree.common_name }}</p>
                                        <p class="text-[10px] text-gray-500 font-mono italic">{{ tree.tree_code }} • {{ tree.species_name }}</p>
                                    </td>
                                    <td class="p-3 font-mono font-bold text-brand-green">
                                        {{ tree.co2_absorption_kg_year }} kg/th
                                    </td>
                                    <td class="p-3">
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold" :class="tree.status === 'adopted' ? 'bg-amber-100 text-amber-800' : 'bg-emerald-100 text-emerald-800'">
                                            {{ tree.status === 'adopted' ? 'Diadopsi' : 'Tersedia' }}
                                        </span>
                                    </td>
                                    <td class="p-3 font-mono text-[11px] text-gray-600">
                                        <span v-if="tree.active_qr_code" class="px-2 py-0.5 rounded bg-gray-100 text-emerald-900 font-semibold">
                                            {{ tree.active_qr_code.token }}
                                        </span>
                                        <span v-else class="text-gray-400 italic">Sudah Terpakai</span>
                                    </td>
                                    <td class="p-3 text-gray-700 font-medium">
                                        {{ tree.current_adoption?.user?.name || '-' }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </LintasanLayout>
</template>
