<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const page = usePage();
const user = computed(() => page.props.auth?.user);
const flash = computed(() => page.props.flash || {});
</script>

<template>
    <div class="min-h-screen bg-slate-50 font-sans text-slate-800 flex flex-col">
        <!-- Flash Toast -->
        <div v-if="flash.success || flash.error" class="fixed top-5 right-5 z-50 max-w-sm rounded-2xl shadow-lg border p-4 bg-white" :class="flash.success ? 'border-emerald-300 text-emerald-900' : 'border-red-300 text-red-900'">
            <div class="flex items-center gap-2 text-sm font-semibold">
                <span>{{ flash.success ? '✓' : '!' }}</span>
                <span>{{ flash.success || flash.error }}</span>
            </div>
        </div>

        <!-- School Admin Navbar -->
        <header class="bg-white border-b border-slate-200 sticky top-0 z-40 shadow-xs">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-18">
                    <div class="flex items-center gap-3">
                        <Link :href="route('school.dashboard')" class="flex items-center gap-2.5">
                            <div class="w-10 h-10 rounded-2xl bg-blue-600 text-white flex items-center justify-center font-bold text-lg shadow-sm">
                                🏫
                            </div>
                            <div>
                                <span class="font-bold text-base text-slate-900 tracking-tight">Portal Guru & Sekolah</span>
                                <p class="text-xs text-blue-600 font-semibold">Program Lintasan × BioTag</p>
                            </div>
                        </Link>
                    </div>

                    <div class="flex items-center gap-3">
                        <Link :href="route('user.dashboard')" class="text-xs font-semibold px-3 py-1.5 rounded-xl bg-emerald-50 text-emerald-800 hover:bg-emerald-100">
                            Beralih ke Dashboard Siswa
                        </Link>
                        <div class="text-right hidden sm:block">
                            <p class="text-xs font-bold text-slate-800">{{ user?.name }}</p>
                            <p class="text-[11px] text-slate-500">Guru / Admin Sekolah</p>
                        </div>
                        <Link :href="route('logout')" method="post" as="button" class="text-xs font-semibold px-3 py-1.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-100">
                            Keluar
                        </Link>
                    </div>
                </div>
            </div>
        </header>

        <main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <slot />
        </main>
    </div>
</template>
