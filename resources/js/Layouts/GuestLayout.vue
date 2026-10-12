<script setup>
import PoweredByFooter from '@/Components/PoweredByFooter.vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="min-h-screen bg-brand-surface font-sans text-brand-forest flex flex-col justify-between selection:bg-brand-lemon selection:text-brand-dark">
        <!-- Top Simple Bar -->
        <header class="py-6 px-4 sm:px-8 border-b border-emerald-100/60 bg-white/70 backdrop-blur-md">
            <div class="max-w-7xl mx-auto flex justify-between items-center">
                <Link :href="route('home')" class="flex items-center gap-3">
                    <img src="/images/lintasan-logo.png" alt="Lintasan" class="h-9 w-auto object-contain" />
                </Link>
                <Link :href="route('home')" class="text-xs font-bold text-gray-500 hover:text-brand-dark transition-colors flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Kembali ke Beranda
                </Link>
            </div>
        </header>

        <!-- Main Form Container -->
        <main class="flex-1 flex flex-col items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
            <div class="w-full max-w-md">
                <slot />
            </div>
        </main>

        <!-- Official Powered By Footer -->
        <PoweredByFooter />
    </div>
</template>
