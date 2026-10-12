<script setup>
import PoweredByFooter from '@/Components/PoweredByFooter.vue';
import { Link, usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

const page = usePage();
const user = computed(() => page.props.auth?.user);
const flash = computed(() => page.props.flash || {});
const dropdownOpen = ref(false);
</script>

<template>
    <div class="min-h-screen bg-brand-surface font-sans text-brand-forest flex flex-col selection:bg-brand-lemon selection:text-brand-dark">
        <!-- Flash Toast Notification -->
        <transition enter-active-class="transform ease-out duration-300 transition" enter-from-class="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2" enter-to-class="translate-y-0 opacity-100 sm:translate-x-0" leave-active-class="transition ease-in duration-100" leave-from-class="opacity-100" leave-to-class="opacity-0">
            <div v-if="flash.success || flash.error" class="fixed top-5 right-5 z-50 max-w-sm rounded-2xl shadow-lg border p-4" :class="flash.success ? 'bg-white border-brand-green/30 text-brand-dark' : 'bg-red-50 border-red-200 text-red-800'">
                <div class="flex items-start gap-3">
                    <span v-if="flash.success" class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-brand-lemon text-brand-dark text-xs font-bold">✓</span>
                    <span v-else class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-red-200 text-red-800 text-xs font-bold">!</span>
                    <div class="text-sm font-medium">{{ flash.success || flash.error }}</div>
                </div>
            </div>
        </transition>

        <!-- User Topbar -->
        <header class="sticky top-0 z-40 bg-white/95 backdrop-blur-md border-b border-emerald-100 shadow-xs">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-20">
                    <!-- Brand Logo: Official Lintasan Logo -->
                    <Link :href="route('user.dashboard')" class="flex items-center gap-3 group">
                        <img
                            src="/images/lintasan-logo.png"
                            alt="Yayasan Lintasan"
                            class="h-9 sm:h-11 w-auto object-contain transition-transform group-hover:scale-105"
                        />
                        <div class="hidden sm:block border-l border-slate-200/90 pl-3">
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-[#EAF7EE] text-[#166534] tracking-wider uppercase font-mono">
                                The Community Space
                            </span>
                            <p class="text-[11px] text-slate-500 font-medium">Jejak Hijau Keluargaku</p>
                        </div>
                    </Link>

                    <!-- User Actions & Profile -->
                    <div class="flex items-center gap-3">
                        <!-- Quick Link to Public Map -->
                        <Link :href="route('public.map')" class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-semibold text-emerald-800 bg-emerald-50 hover:bg-emerald-100 transition-colors">
                            <svg class="w-3.5 h-3.5 text-emerald-700" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                            <span>Peta Pohon</span>
                        </Link>

                        <!-- Role Badges & Switchers -->
                        <Link v-if="user?.role === 'officer' || user?.role === 'biotag_super_admin'" :href="route('officer.dashboard')" class="hidden sm:inline-flex items-center px-3 py-1.5 rounded-xl text-xs font-semibold bg-emerald-100 text-emerald-900 hover:bg-emerald-200 transition-colors">
                            Petugas Lapangan
                        </Link>
                        <Link v-if="user?.role === 'school_admin' || user?.role === 'biotag_super_admin'" :href="route('school.dashboard')" class="hidden sm:inline-flex items-center px-3 py-1.5 rounded-xl text-xs font-semibold bg-blue-50 text-blue-700 hover:bg-blue-100 transition-colors">
                            Mode Guru
                        </Link>
                        <Link v-if="user?.role === 'lintasan_admin' || user?.role === 'biotag_super_admin'" :href="route('lintasan.dashboard')" class="hidden sm:inline-flex items-center px-3 py-1.5 rounded-xl text-xs font-semibold bg-emerald-100 text-emerald-900 hover:bg-emerald-200 transition-colors">
                            Admin Lintasan
                        </Link>
                        <Link v-if="user?.role === 'biotag_super_admin'" :href="route('biotag.dashboard')" class="hidden sm:inline-flex items-center px-3 py-1.5 rounded-xl text-xs font-semibold bg-purple-100 text-purple-900 hover:bg-purple-200 transition-colors">
                            Super Admin BioTag
                        </Link>

                        <!-- User Profile Dropdown -->
                        <div class="relative">
                            <button @click="dropdownOpen = !dropdownOpen" class="flex items-center gap-2.5 p-1.5 pr-3 rounded-2xl border border-gray-200 hover:border-brand-green/40 hover:bg-emerald-50/40 transition-all">
                                <div class="w-8 h-8 rounded-xl bg-brand-green text-white font-bold text-xs flex items-center justify-center">
                                    {{ user?.name ? user.name.charAt(0).toUpperCase() : 'U' }}
                                </div>
                                <div class="text-left hidden sm:block">
                                    <p class="text-xs font-bold text-brand-dark leading-tight">{{ user?.name }}</p>
                                    <p class="text-[10px] text-gray-500 capitalize">{{ user?.role?.replace('_', ' ') }}</p>
                                </div>
                                <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>

                            <div v-show="dropdownOpen" @click="dropdownOpen = false" class="fixed inset-0 z-40"></div>

                            <div v-show="dropdownOpen" class="absolute right-0 mt-2 w-52 bg-white rounded-2xl shadow-xl border border-gray-100 py-2 z-50">
                                <div class="px-4 py-2 border-b border-gray-100">
                                    <p class="text-xs font-semibold text-gray-900">{{ user?.name }}</p>
                                    <p class="text-[11px] text-gray-500 truncate">{{ user?.email }}</p>
                                </div>
                                <Link :href="route('user.dashboard')" class="block px-4 py-2 text-xs font-medium text-gray-700 hover:bg-emerald-50 hover:text-brand-dark">Dashboard Saya</Link>
                                <Link v-if="user?.role === 'officer' || user?.role === 'biotag_super_admin'" :href="route('officer.dashboard')" class="block px-4 py-2 text-xs font-medium text-emerald-800 bg-emerald-50">Inspeksi Petugas Lapangan</Link>
                                <Link :href="route('profile.edit')" class="block px-4 py-2 text-xs font-medium text-gray-700 hover:bg-emerald-50 hover:text-brand-dark">Pengaturan Akun</Link>
                                <div class="border-t border-gray-100 my-1"></div>
                                <Link :href="route('logout')" method="post" as="button" class="w-full text-left px-4 py-2 text-xs font-semibold text-red-600 hover:bg-red-50">
                                    Keluar (Logout)
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Body -->
        <main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <slot />
        </main>

        <!-- Powered By Footer -->
        <PoweredByFooter />
    </div>
</template>
