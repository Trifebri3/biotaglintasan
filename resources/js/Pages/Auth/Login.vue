<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const googleForm = useForm({});
const quickForm = useForm({
    role_key: 'andi',
});

function submit() {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
}

function loginWithGoogle() {
    googleForm.post(route('login.google'));
}

function quickLogin(roleKey) {
    quickForm.role_key = roleKey;
    quickForm.post(route('login.quick'));
}
</script>

<template>
    <GuestLayout>
        <Head title="Masuk — Lintasan × BioTag" />

        <div class="bg-white rounded-3xl p-8 border border-emerald-100 shadow-xl space-y-6">
            <!-- Header -->
            <div class="text-center space-y-2">
                <span class="text-xs font-bold uppercase tracking-wider text-emerald-800 bg-emerald-50 px-3 py-1 rounded-full">
                    Jejak Hijau Keluargaku
                </span>
                <h1 class="text-2xl font-black text-brand-dark tracking-tight">
                    Masuk ke Akun Anda
                </h1>
                <p class="text-xs text-gray-500">
                    Akses catatan emisi keluarga, aksi hijau harian, dan pohon adopsi Anda.
                </p>
            </div>

            <!-- Google Sign In Button (Prominent) -->
            <div>
                <button
                    type="button"
                    @click="loginWithGoogle"
                    :disabled="googleForm.processing"
                    class="w-full flex items-center justify-center gap-3 py-3.5 px-4 rounded-2xl border border-gray-200 bg-white hover:bg-gray-50 text-gray-800 font-bold text-sm shadow-xs transition-all transform active:scale-98"
                >
                    <!-- Google SVG Logo -->
                    <svg class="w-5 h-5" viewBox="0 0 24 24">
                        <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" />
                        <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" />
                        <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z" />
                        <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z" />
                    </svg>
                    <span>Masuk dengan Google</span>
                </button>
            </div>

            <!-- Divider -->
            <div class="relative flex items-center justify-center">
                <div class="border-t border-gray-200 w-full"></div>
                <span class="bg-white px-3 text-[11px] font-bold text-gray-400 uppercase tracking-wider relative">
                    atau login dengan email
                </span>
            </div>

            <!-- Email & Password Form -->
            <form @submit.prevent="submit" class="space-y-4">
                <div>
                    <InputLabel for="email" value="Email" class="text-xs font-bold text-gray-700" />
                    <TextInput
                        id="email"
                        type="email"
                        class="mt-1 block w-full rounded-2xl text-xs py-2.5 px-3.5 border-gray-200"
                        v-model="form.email"
                        placeholder="nama@email.com"
                        required
                        autofocus
                    />
                    <InputError class="mt-1 text-xs" :message="form.errors.email" />
                </div>

                <div>
                    <InputLabel for="password" value="Kata Sandi" class="text-xs font-bold text-gray-700" />
                    <TextInput
                        id="password"
                        type="password"
                        class="mt-1 block w-full rounded-2xl text-xs py-2.5 px-3.5 border-gray-200"
                        v-model="form.password"
                        placeholder="••••••••"
                        required
                    />
                    <InputError class="mt-1 text-xs" :message="form.errors.password" />
                </div>

                <div class="flex items-center justify-between text-xs">
                    <label class="flex items-center">
                        <Checkbox name="remember" v-model:checked="form.remember" class="rounded text-brand-green" />
                        <span class="ms-2 text-gray-600">Ingat saya</span>
                    </label>

                    <Link
                        v-if="canResetPassword"
                        :href="route('password.request')"
                        class="font-semibold text-emerald-800 hover:text-brand-dark"
                    >
                        Lupa kata sandi?
                    </Link>
                </div>

                <button
                    type="submit"
                    :disabled="form.processing"
                    class="w-full py-3 rounded-2xl bg-brand-green hover:bg-brand-dark text-white font-extrabold text-xs shadow-md transition-all"
                >
                    Masuk
                </button>
            </form>

            <!-- Quick Demo Login Switcher -->
            <div class="pt-4 border-t border-gray-100 space-y-2">
                <span class="text-[11px] font-bold text-gray-400 uppercase tracking-wider block text-center">
                    Akses Cepat Akun Demo (1-Klik)
                </span>
                <div class="grid grid-cols-2 gap-2 text-[11px]">
                    <button
                        type="button"
                        @click="quickLogin('andi')"
                        class="p-2 rounded-xl border border-emerald-200 bg-emerald-50/70 hover:bg-emerald-100 text-emerald-900 font-bold text-left transition-colors"
                    >
                        Keluarga Andi
                        <span class="block text-[9px] font-normal text-emerald-700">Siswa & Keluarga</span>
                    </button>
                    <button
                        type="button"
                        @click="quickLogin('officer')"
                        class="p-2 rounded-xl border border-teal-200 bg-teal-50/70 hover:bg-teal-100 text-teal-900 font-bold text-left transition-colors"
                    >
                        Pak Hendra
                        <span class="block text-[9px] font-normal text-teal-700">Pengawas Lapangan</span>
                    </button>
                    <button
                        type="button"
                        @click="quickLogin('guru')"
                        class="p-2 rounded-xl border border-blue-200 bg-blue-50/70 hover:bg-blue-100 text-blue-900 font-bold text-left transition-colors"
                    >
                        Ibu Dewi
                        <span class="block text-[9px] font-normal text-blue-700">Guru / Sekolah</span>
                    </button>
                    <button
                        type="button"
                        @click="quickLogin('lintasan')"
                        class="p-2 rounded-xl border border-amber-200 bg-amber-50/70 hover:bg-amber-100 text-amber-900 font-bold text-left transition-colors"
                    >
                        Admin Lintasan
                        <span class="block text-[9px] font-normal text-amber-700">Yayasan Lintasan</span>
                    </button>
                </div>
                <button
                    type="button"
                    @click="quickLogin('biotag')"
                    class="w-full p-2 rounded-xl border border-purple-200 bg-purple-50/70 hover:bg-purple-100 text-purple-900 font-bold text-[11px] text-center transition-colors"
                >
                    Super Admin BioTag (Platform Ops & Audit)
                </button>
            </div>

            <div class="text-center text-xs text-gray-500 pt-2">
                Belum punya akun?
                <Link :href="route('register')" class="font-extrabold text-brand-green hover:underline">
                    Daftar di sini
                </Link>
            </div>
        </div>
    </GuestLayout>
</template>
