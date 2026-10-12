<script setup>
import PoweredByFooter from '@/Components/PoweredByFooter.vue';
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const page = usePage();
const user = computed(() => page.props.auth?.user);
const flash = computed(() => page.props.flash || {});
</script>

<template>
    <div class="min-h-screen bg-slate-50 font-sans text-slate-800 flex flex-col">
        <!-- Toast Notification -->
        <div v-if="flash.success || flash.error" class="fixed top-5 right-5 z-50 max-w-sm rounded-2xl shadow-xl border p-4 bg-white" :class="flash.success ? 'border-emerald-300 text-emerald-900' : 'border-red-300 text-red-900'">
            <div class="flex items-center gap-2 text-sm font-semibold">
                <svg v-if="flash.success" class="w-5 h-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <svg v-else class="w-5 h-5 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                <span>{{ flash.success || flash.error }}</span>
            </div>
        </div>

        <!-- Top Navigation Bar -->
        <header class="bg-white border-b border-slate-200 sticky top-0 z-40 shadow-xs">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-20">
                    <div class="flex items-center gap-4">
                        <Link :href="route('officer.dashboard')" class="flex items-center gap-3">
                            <img src="/images/lintasan-logo.png" alt="Lintasan" class="h-9 w-auto object-contain" />
                            <div class="hidden sm:block border-l border-slate-200 pl-3">
                                <span class="text-xs font-bold uppercase tracking-wider text-emerald-800 bg-emerald-50 px-2.5 py-0.5 rounded-md">
                                    Pengawas Lapangan BioTag
                                </span>
                                <p class="text-[11px] text-slate-500 font-medium">Inspeksi & Update Foto Pohon</p>
                            </div>
                        </Link>
                    </div>

                    <div class="flex items-center gap-3">
                        <Link :href="route('public.map')" class="text-xs font-semibold px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 transition-colors flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                            Peta Pohon
                        </Link>

                        <div class="text-right hidden sm:block">
                            <p class="text-xs font-bold text-slate-900">{{ user?.name }}</p>
                            <span class="text-[10px] text-emerald-700 font-semibold">Petugas Verifikasi</span>
                        </div>

                        <Link :href="route('logout')" method="post" as="button" class="text-xs font-bold px-3 py-1.5 rounded-xl border border-slate-200 text-red-600 hover:bg-red-50 transition-colors">
                            Keluar
                        </Link>
                    </div>
                </div>
            </div>
        </header>

        <main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <slot />
        </main>

        <!-- Powered By Footer -->
        <PoweredByFooter />
    </div>
</template>
