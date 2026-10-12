<script setup>
import PoweredByFooter from '@/Components/PoweredByFooter.vue';
import { Link, usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

const page = usePage();
const user = computed(() => page.props.auth?.user);
const flash = computed(() => page.props.flash || {});
const mobileMenuOpen = ref(false);
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

        <!-- Main Navigation Header -->
        <header class="sticky top-0 z-40 bg-white/90 backdrop-blur-md border-b border-emerald-100/60 shadow-xs">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-20">
                    <!-- Brand Logo: Official Lintasan Logo -->
                    <Link :href="route('home')" class="flex items-center gap-3 group">
                        <img
                            src="/images/lintasan-logo.png"
                            alt="Yayasan Lintasan"
                            class="h-9 sm:h-11 w-auto object-contain transition-transform group-hover:scale-105"
                        />
                        <div class="hidden sm:block border-l border-slate-200/90 pl-3">
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-brand-lemon/70 text-brand-dark tracking-wide uppercase font-mono">
                                The Community Space
                            </span>
                            <p class="text-xs text-emerald-800/80 font-medium tracking-tight">Jejak Hijau Keluargaku</p>
                        </div>
                    </Link>

                    <!-- Desktop Nav Links -->
                    <nav class="hidden md:flex items-center gap-1 lg:gap-2">
                        <Link :href="route('home')" class="px-3.5 py-2 rounded-xl text-sm font-semibold transition-colors" :class="route().current('home') ? 'text-brand-green bg-emerald-50/80' : 'text-gray-600 hover:text-brand-dark hover:bg-gray-50'">
                            Beranda
                        </Link>
                        <Link :href="route('public.map')" class="px-3.5 py-2 rounded-xl text-sm font-semibold transition-colors" :class="route().current('public.map') ? 'text-brand-green bg-emerald-50/80' : 'text-gray-600 hover:text-brand-dark hover:bg-gray-50'">
                            Peta Pohon
                        </Link>
                        <Link :href="route('public.education')" class="px-3.5 py-2 rounded-xl text-sm font-semibold transition-colors" :class="route().current('public.education') ? 'text-brand-green bg-emerald-50/80' : 'text-gray-600 hover:text-brand-dark hover:bg-gray-50'">
                            Pusat Edukasi
                        </Link>
                        <Link :href="route('public.about')" class="px-3.5 py-2 rounded-xl text-sm font-semibold transition-colors" :class="route().current('public.about') ? 'text-brand-green bg-emerald-50/80' : 'text-gray-600 hover:text-brand-dark hover:bg-gray-50'">
                            Tentang & BioTag
                        </Link>
                    </nav>

                    <!-- Auth Action Buttons -->
                    <div class="hidden md:flex items-center gap-3">
                        <template v-if="user">
                            <Link :href="route('dashboard')" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-brand-green hover:bg-brand-dark text-white font-semibold text-sm shadow-sm transition-all shadow-brand-green/20">
                                <span>Buka Dashboard</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </Link>
                        </template>
                        <template v-else>
                            <Link :href="route('login')" class="px-4 py-2 rounded-xl text-sm font-semibold text-brand-dark hover:bg-emerald-50 transition-colors">
                                Masuk
                            </Link>
                            <Link :href="route('register')" class="inline-flex items-center px-5 py-2.5 rounded-xl bg-brand-lemon hover:bg-brand-lemon-hover text-brand-dark font-bold text-sm shadow-sm transition-transform active:scale-95">
                                Mulai Aksi Hijau
                            </Link>
                        </template>
                    </div>

                    <!-- Mobile Menu Button -->
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden p-2 rounded-xl text-gray-600 hover:text-brand-dark hover:bg-gray-100">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path v-if="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Mobile Menu Dropdown -->
                <div v-show="mobileMenuOpen" class="md:hidden py-4 border-t border-gray-100 space-y-2">
                    <Link :href="route('home')" class="block px-4 py-2 rounded-xl text-sm font-semibold text-gray-700 hover:bg-emerald-50">Beranda</Link>
                    <Link :href="route('public.map')" class="block px-4 py-2 rounded-xl text-sm font-semibold text-gray-700 hover:bg-emerald-50">Peta Pohon</Link>
                    <Link :href="route('public.education')" class="block px-4 py-2 rounded-xl text-sm font-semibold text-gray-700 hover:bg-emerald-50">Pusat Edukasi</Link>
                    <Link :href="route('public.about')" class="block px-4 py-2 rounded-xl text-sm font-semibold text-gray-700 hover:bg-emerald-50">Tentang & BioTag</Link>
                    <div class="pt-2 border-t border-gray-100 flex flex-col gap-2">
                        <Link v-if="user" :href="route('dashboard')" class="text-center px-4 py-2.5 rounded-xl bg-brand-green text-white font-semibold text-sm">Buka Dashboard</Link>
                        <template v-else>
                            <Link :href="route('login')" class="text-center px-4 py-2 rounded-xl text-sm font-semibold text-brand-dark border border-gray-200">Masuk</Link>
                            <Link :href="route('register')" class="text-center px-4 py-2.5 rounded-xl bg-brand-lemon text-brand-dark font-bold text-sm">Mulai Aksi Hijau</Link>
                        </template>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content Slot -->
        <main class="flex-1">
            <slot />
        </main>

        <!-- Footer -->
        <footer class="bg-white border-t border-emerald-100/70 pt-12 pb-8 mt-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-10">
                    <div class="md:col-span-2 space-y-3">
                        <div class="flex items-center gap-2">
                            <span class="font-black text-xl text-brand-dark tracking-tight">LINTASAN</span>
                            <span class="text-xs px-2 py-0.5 rounded-md bg-emerald-100 text-brand-dark font-bold">The Community Space</span>
                        </div>
                        <p class="text-sm text-gray-600 max-w-md leading-relaxed">
                            Platform edukasi dan aksi lingkungan berbasis keluarga, sekolah, dan komunitas. Memahami jejak emisi rumah tangga, aksi nyata merawat pohon, dan terhubung melalui kode QR unik.
                        </p>
                        <div class="pt-2">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-50 border border-emerald-200 text-xs font-medium text-emerald-800">
                                <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                                Program Mitra: Yayasan Lintasan × BioTag
                            </span>
                        </div>
                    </div>

                    <div>
                        <h4 class="font-bold text-xs uppercase tracking-wider text-gray-500 mb-3">Navigasi Utama</h4>
                        <ul class="space-y-2 text-sm text-gray-600">
                            <li><Link :href="route('home')" class="hover:text-brand-green transition-colors">Beranda</Link></li>
                            <li><Link :href="route('public.map')" class="hover:text-brand-green transition-colors">Peta Pohon Terlindungi</Link></li>
                            <li><Link :href="route('public.education')" class="hover:text-brand-green transition-colors">Pusat Edukasi Keluarga</Link></li>
                            <li><Link :href="route('public.about')" class="hover:text-brand-green transition-colors">Transparansi & Metodologi</Link></li>
                        </ul>
                    </div>

                    <div>
                        <h4 class="font-bold text-xs uppercase tracking-wider text-gray-500 mb-3">Teknologi & Etika</h4>
                        <p class="text-xs text-gray-500 leading-relaxed mb-3">
                            Platform dilindungi prinsip <span class="font-semibold text-gray-700">Privacy by Design</span> untuk menjaga kerahasiaan identitas dan koordinat anak/keluarga.
                        </p>
                        <div class="text-xs text-emerald-800 font-semibold bg-brand-surface p-2.5 rounded-xl border border-emerald-100">
                            Technology platform by BioTag<br/>
                            <span class="text-[11px] font-normal text-gray-500">Yota Adiwidya Center</span>
                        </div>
                    </div>
                </div>

                <div class="pt-6 border-t border-gray-100 flex flex-col sm:flex-row justify-between items-center text-xs text-gray-500 gap-3">
                    <p>© 2026 Yayasan Lintasan — Program The Community Space. Seluruh hak cipta dilindungi.</p>
                    <p class="flex items-center gap-4">
                        <span>Estimasi Karbon Berstandar ESDM & IPCC</span>
                        <span>•</span>
                        <span>QR Platform by BioTag</span>
                    </p>
                </div>
            </div>
        </footer>

        <!-- Powered By Footer Section -->
        <PoweredByFooter />
    </div>
</template>
