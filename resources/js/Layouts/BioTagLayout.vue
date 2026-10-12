<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const page = usePage();
const user = computed(() => page.props.auth?.user);
const flash = computed(() => page.props.flash || {});
</script>

<template>
    <div class="min-h-screen bg-slate-900 font-sans text-slate-100 flex flex-col selection:bg-brand-lemon selection:text-slate-900">
        <!-- Flash Toast -->
        <div v-if="flash.success || flash.error" class="fixed top-5 right-5 z-50 max-w-sm rounded-2xl shadow-xl border p-4 bg-slate-800" :class="flash.success ? 'border-brand-lemon/40 text-brand-lemon' : 'border-red-400 text-red-200'">
            <div class="flex items-center gap-2 text-sm font-semibold">
                <span>{{ flash.success ? '✓' : '!' }}</span>
                <span>{{ flash.success || flash.error }}</span>
            </div>
        </div>

        <header class="bg-slate-950/80 backdrop-blur-md border-b border-slate-800 sticky top-0 z-40">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-18">
                    <div class="flex items-center gap-3">
                        <Link :href="route('biotag.dashboard')" class="flex items-center gap-2.5">
                            <div class="w-10 h-10 rounded-2xl bg-brand-lemon text-slate-950 flex items-center justify-center font-black text-lg shadow-md shadow-brand-lemon/20">
                                BT
                            </div>
                            <div>
                                <span class="font-black text-base text-white tracking-wider">BioTag Super Admin</span>
                                <p class="text-[11px] text-emerald-400 font-mono">Yota Adiwidya Center • Platform Ops</p>
                            </div>
                        </Link>
                    </div>

                    <div class="flex items-center gap-3">
                        <Link :href="route('user.dashboard')" class="text-xs font-semibold px-3 py-1.5 rounded-xl bg-slate-800 text-slate-300 hover:bg-slate-700">
                            Dashboard User
                        </Link>
                        <Link :href="route('lintasan.dashboard')" class="text-xs font-semibold px-3 py-1.5 rounded-xl bg-slate-800 text-slate-300 hover:bg-slate-700">
                            Portal Lintasan
                        </Link>
                        <div class="text-right hidden sm:block">
                            <p class="text-xs font-bold text-white">{{ user?.name }}</p>
                            <span class="text-[10px] px-2 py-0.5 rounded-full bg-brand-lemon/20 text-brand-lemon font-mono font-bold">SUPER ADMIN</span>
                        </div>
                        <Link :href="route('logout')" method="post" as="button" class="text-xs font-semibold px-3 py-1.5 rounded-xl bg-red-950 text-red-300 hover:bg-red-900 border border-red-800">
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
