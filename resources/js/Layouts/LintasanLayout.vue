<script setup>
import PoweredByFooter from '@/Components/PoweredByFooter.vue';
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const page = usePage();
const user = computed(() => page.props.auth?.user);
const flash = computed(() => page.props.flash || {});
</script>

<template>
    <div class="min-h-screen bg-[#F7F9F6] font-sans text-brand-forest flex flex-col">
        <!-- Flash Toast -->
        <div v-if="flash.success || flash.error" class="fixed top-5 right-5 z-50 max-w-sm rounded-2xl shadow-lg border p-4 bg-white" :class="flash.success ? 'border-brand-green/30 text-brand-dark' : 'border-red-300 text-red-900'">
            <div class="flex items-center gap-2 text-sm font-semibold">
                <span>{{ flash.success ? '✓' : '!' }}</span>
                <span>{{ flash.success || flash.error }}</span>
            </div>
        </div>

        <header class="bg-white border-b border-emerald-100 sticky top-0 z-40 shadow-xs">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-18">
                        <Link :href="route('lintasan.dashboard')" class="flex items-center gap-3 group">
                            <img
                                src="/images/lintasan-logo.png"
                                alt="Yayasan Lintasan Admin"
                                class="h-9 sm:h-10 w-auto object-contain transition-transform group-hover:scale-105"
                            />
                            <div class="hidden sm:block border-l border-slate-200/90 pl-3">
                                <span class="font-extrabold text-sm text-brand-dark tracking-tight">Admin Yayasan Lintasan</span>
                                <p class="text-[11px] text-emerald-800 font-semibold">Pengelola Program & Pohon Komunitas</p>
                            </div>
                        </Link>

                    <div class="flex items-center gap-3">
                        <Link :href="route('public.map')" class="text-xs font-semibold px-3 py-1.5 rounded-xl bg-emerald-50 text-emerald-800 hover:bg-emerald-100">
                            Lihat Peta Publik
                        </Link>
                        <div class="text-right hidden sm:block">
                            <p class="text-xs font-bold text-slate-800">{{ user?.name }}</p>
                            <p class="text-[11px] text-slate-500">Program Coordinator</p>
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

        <!-- Powered By Footer -->
        <PoweredByFooter />
    </div>
</template>
