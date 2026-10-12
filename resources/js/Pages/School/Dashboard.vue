<script setup>
import SchoolLayout from '@/Layouts/SchoolLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps({
    school: {
        type: Object,
        default: null,
    },
    memberships: {
        type: Array,
        default: () => [],
    },
    classes: {
        type: Array,
        default: () => [],
    },
    stats: {
        type: Object,
        default: () => ({ total_students: 0, approved_students: 0, pending_students: 0, total_classes: 0 }),
    },
});

// Class creation form
const classForm = useForm({
    school_id: props.school?.id,
    name: '',
    grade_level: '4',
    academic_year: '2026/2027',
});

function createClass() {
    classForm.post(route('school.create-class'), {
        preserveScroll: true,
        onSuccess: () => classForm.reset('name'),
    });
}

// Student verification
function verifyStudent(membershipId, classId = null) {
    const verifyForm = useForm({
        school_class_id: classId,
    });
    verifyForm.post(route('school.verify-student', { id: membershipId }), {
        preserveScroll: true,
    });
}
</script>

<template>
    <Head title="Portal Guru & Sekolah — Lintasan" />

    <SchoolLayout>
        <div class="space-y-8">
            <!-- Header Banner -->
            <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-blue-700 bg-blue-50 px-3 py-1 rounded-full">
                        Sekolah Mitra Terdaftar • Kode: {{ school?.code }}
                    </span>
                    <h1 class="text-2xl sm:text-3xl font-black text-slate-900 mt-2">
                        {{ school?.name || 'Sekolah Mitra Lintasan' }}
                    </h1>
                    <p class="text-xs text-slate-500 mt-1">{{ school?.address }} • {{ school?.city }}</p>
                </div>

                <!-- Stats Pill -->
                <div class="grid grid-cols-3 gap-3 bg-slate-50 p-3 rounded-2xl border border-slate-100 text-center shrink-0">
                    <div class="px-3">
                        <span class="block text-xl font-black text-slate-900">{{ stats.total_students }}</span>
                        <span class="text-[10px] text-slate-500 font-bold uppercase">Siswa Terdaftar</span>
                    </div>
                    <div class="px-3 border-x border-slate-200">
                        <span class="block text-xl font-black text-emerald-600">{{ stats.approved_students }}</span>
                        <span class="text-[10px] text-slate-500 font-bold uppercase">Terverifikasi</span>
                    </div>
                    <div class="px-3">
                        <span class="block text-xl font-black text-amber-600">{{ stats.pending_students }}</span>
                        <span class="text-[10px] text-slate-500 font-bold uppercase">Menunggu</span>
                    </div>
                </div>
            </div>

            <!-- Main Content: Student Verification & Classes -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                <!-- Students Table & Verification Queue -->
                <div class="lg:col-span-8 bg-white rounded-3xl border border-slate-200 p-6 shadow-sm space-y-5">
                    <div class="flex justify-between items-center">
                        <div>
                            <h3 class="font-extrabold text-lg text-slate-900">Daftar Siswa & Antrean Verifikasi</h3>
                            <p class="text-xs text-slate-500">Verifikasi siswa agar mereka dapat mengakses dashboard dan mengadopsi pohon</p>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs">
                            <thead class="bg-slate-50 text-slate-600 uppercase font-bold text-[10px] border-b border-slate-200">
                                <tr>
                                    <th class="p-3">Nama Siswa</th>
                                    <th class="p-3">Email</th>
                                    <th class="p-3">Kelas</th>
                                    <th class="p-3">Status</th>
                                    <th class="p-3 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <tr v-for="m in memberships" :key="m.id" class="hover:bg-slate-50/60 transition-colors">
                                    <td class="p-3 font-bold text-slate-900">{{ m.user.name }}</td>
                                    <td class="p-3 text-slate-500 font-mono text-[11px]">{{ m.user.email }}</td>
                                    <td class="p-3 text-slate-600">
                                        {{ m.school_class?.name || 'Belum Ditugaskan' }}
                                    </td>
                                    <td class="p-3">
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold" :class="m.status === 'approved' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800'">
                                            {{ m.status === 'approved' ? 'Aktif' : 'Menunggu' }}
                                        </span>
                                    </td>
                                    <td class="p-3 text-right">
                                        <button
                                            v-if="m.status === 'pending'"
                                            @click="verifyStudent(m.id, classes[0]?.id)"
                                            class="px-3 py-1 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-[11px] shadow-xs"
                                        >
                                            Verifikasi Siswa
                                        </button>
                                        <span v-else class="text-emerald-700 text-[11px] font-semibold">
                                            ✓ Diverifikasi
                                        </span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Classes Manager Sidebar -->
                <div class="lg:col-span-4 space-y-6">
                    <!-- Add Class Form -->
                    <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-sm space-y-4">
                        <h3 class="font-extrabold text-base text-slate-900">Tambah Kelas Baru</h3>
                        <form @submit.prevent="createClass" class="space-y-3">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Nama Kelas</label>
                                <input
                                    v-model="classForm.name"
                                    type="text"
                                    placeholder="Contoh: Kelas 4B - Melati"
                                    class="w-full px-3.5 py-2.5 rounded-2xl border border-slate-200 text-xs focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500"
                                    required
                                />
                            </div>
                            <div class="grid grid-cols-2 gap-2">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1">Tingkat</label>
                                    <input v-model="classForm.grade_level" type="text" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs" />
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1">Tahun Ajaran</label>
                                    <input v-model="classForm.academic_year" type="text" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs" />
                                </div>
                            </div>
                            <button
                                type="submit"
                                :disabled="classForm.processing"
                                class="w-full py-2.5 rounded-2xl bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs"
                            >
                                Simpan Kelas
                            </button>
                        </form>
                    </div>

                    <!-- Existing Classes List -->
                    <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-sm space-y-3">
                        <h4 class="text-xs font-extrabold uppercase tracking-wider text-slate-500">Kelas Aktif ({{ classes.length }})</h4>
                        <div class="space-y-2">
                            <div v-for="c in classes" :key="c.id" class="p-3 rounded-2xl bg-slate-50 border border-slate-100 flex items-center justify-between text-xs">
                                <div>
                                    <p class="font-bold text-slate-900">{{ c.name }}</p>
                                    <p class="text-[10px] text-slate-500">{{ c.academic_year }} • Tingkat {{ c.grade_level }}</p>
                                </div>
                                <span class="text-slate-400 font-mono text-[10px]">ID: {{ c.id }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </SchoolLayout>
</template>
