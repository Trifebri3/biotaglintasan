<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const googleForm = useForm({});

function submit() {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
}

function registerWithGoogle() {
    googleForm.post(route('login.google'));
}
</script>

<template>
    <GuestLayout>
        <Head title="Daftar — Lintasan × BioTag" />

        <div class="bg-white rounded-3xl p-8 border border-emerald-100 shadow-xl space-y-6">
            <div class="text-center space-y-2">
                <span class="text-xs font-bold uppercase tracking-wider text-emerald-800 bg-emerald-50 px-3 py-1 rounded-full">
                    The Community Space
                </span>
                <h1 class="text-2xl font-black text-brand-dark tracking-tight">
                    Mulai Aksi Hijau Keluarga
                </h1>
                <p class="text-xs text-gray-500">
                    Daftar akun keluarga atau siswa untuk terhubung dengan pohon dan sekolah.
                </p>
            </div>

            <!-- Google Sign Up Button -->
            <div>
                <button
                    type="button"
                    @click="registerWithGoogle"
                    :disabled="googleForm.processing"
                    class="w-full flex items-center justify-center gap-3 py-3.5 px-4 rounded-2xl border border-gray-200 bg-white hover:bg-gray-50 text-gray-800 font-bold text-sm shadow-xs transition-all transform active:scale-98"
                >
                    <svg class="w-5 h-5" viewBox="0 0 24 24">
                        <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" />
                        <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" />
                        <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z" />
                        <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z" />
                    </svg>
                    <span>Daftar Cepat dengan Google</span>
                </button>
            </div>

            <div class="relative flex items-center justify-center">
                <div class="border-t border-gray-200 w-full"></div>
                <span class="bg-white px-3 text-[11px] font-bold text-gray-400 uppercase tracking-wider relative">
                    atau isi formulir pendaftaran
                </span>
            </div>

            <form @submit.prevent="submit" class="space-y-4">
                <div>
                    <InputLabel for="name" value="Nama Lengkap / Nama Keluarga" class="text-xs font-bold text-gray-700" />
                    <TextInput
                        id="name"
                        type="text"
                        class="mt-1 block w-full rounded-2xl text-xs py-2.5 px-3.5 border-gray-200"
                        v-model="form.name"
                        placeholder="Contoh: Keluarga Andi"
                        required
                        autofocus
                    />
                    <InputError class="mt-1 text-xs" :message="form.errors.name" />
                </div>

                <div>
                    <InputLabel for="email" value="Alamat Email" class="text-xs font-bold text-gray-700" />
                    <TextInput
                        id="email"
                        type="email"
                        class="mt-1 block w-full rounded-2xl text-xs py-2.5 px-3.5 border-gray-200"
                        v-model="form.email"
                        placeholder="andi@email.com"
                        required
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
                        placeholder="Minimal 8 karakter"
                        required
                    />
                    <InputError class="mt-1 text-xs" :message="form.errors.password" />
                </div>

                <div>
                    <InputLabel for="password_confirmation" value="Konfirmasi Kata Sandi" class="text-xs font-bold text-gray-700" />
                    <TextInput
                        id="password_confirmation"
                        type="password"
                        class="mt-1 block w-full rounded-2xl text-xs py-2.5 px-3.5 border-gray-200"
                        v-model="form.password_confirmation"
                        placeholder="Ulangi kata sandi"
                        required
                    />
                    <InputError class="mt-1 text-xs" :message="form.errors.password_confirmation" />
                </div>

                <button
                    type="submit"
                    :disabled="form.processing"
                    class="w-full py-3.5 rounded-2xl bg-brand-lemon hover:bg-brand-lemon-hover text-brand-dark font-extrabold text-xs shadow-md transition-all active:scale-95"
                >
                    Daftar Sekarang
                </button>
            </form>

            <div class="text-center text-xs text-gray-500 pt-2">
                Sudah memiliki akun?
                <Link :href="route('login')" class="font-extrabold text-brand-green hover:underline">
                    Masuk di sini
                </Link>
            </div>
        </div>
    </GuestLayout>
</template>
