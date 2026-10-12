<script setup>
import BioTagLayout from '@/Layouts/BioTagLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps({
    stats: {
        type: Object,
        default: () => ({ users: 0, trees: 0, qrs: 0, schools: 0 }),
    },
    healthStatus: {
        type: Object,
        default: () => ({}),
    },
    recentQRs: {
        type: Array,
        default: () => [],
    },
    auditLogs: {
        type: Array,
        default: () => [],
    },
    emissionFactors: {
        type: Array,
        default: () => [],
    },
});

function toggleQr(qrId) {
    const form = useForm({});
    form.post(route('biotag.toggle-qr', { id: qrId }), {
        preserveScroll: true,
    });
}
</script>

<template>
    <Head title="BioTag Super Admin — Platform Integrity & Operations" />

    <BioTagLayout>
        <div class="space-y-8">
            <!-- Header Section -->
            <div class="bg-slate-950 p-6 sm:p-8 rounded-3xl border border-slate-800 shadow-xl flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-950 border border-emerald-800 text-brand-lemon text-xs font-mono font-bold">
                        <span class="w-2 h-2 rounded-full bg-brand-lemon animate-ping"></span>
                        BioTag Core Platform Engine • Yota Adiwidya Center
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-black text-white mt-2 tracking-tight">
                        Platform Operations & Audit Integrity
                    </h1>
                    <p class="text-xs text-slate-400 mt-1">Kewenangan teknis tertinggi platform: pemantauan sistem, token QR kriptografis, dan audit trail.</p>
                </div>

                <!-- Live Metrics -->
                <div class="grid grid-cols-4 gap-3 bg-slate-900 p-3 rounded-2xl border border-slate-800 text-center shrink-0">
                    <div class="px-2">
                        <span class="block text-lg font-mono font-bold text-brand-lemon">{{ stats.trees }}</span>
                        <span class="text-[9px] text-slate-400 uppercase font-bold">Pohon</span>
                    </div>
                    <div class="px-2 border-l border-slate-800">
                        <span class="block text-lg font-mono font-bold text-emerald-400">{{ stats.qrs }}</span>
                        <span class="text-[9px] text-slate-400 uppercase font-bold">QR Token</span>
                    </div>
                    <div class="px-2 border-l border-slate-800">
                        <span class="block text-lg font-mono font-bold text-white">{{ stats.schools }}</span>
                        <span class="text-[9px] text-slate-400 uppercase font-bold">Tenant</span>
                    </div>
                    <div class="px-2 border-l border-slate-800">
                        <span class="block text-lg font-mono font-bold text-purple-400">{{ stats.users }}</span>
                        <span class="text-[9px] text-slate-400 uppercase font-bold">Akun</span>
                    </div>
                </div>
            </div>

            <!-- Health Status Bar -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="p-4 rounded-2xl bg-slate-950 border border-slate-800">
                    <span class="text-[10px] text-slate-400 font-mono uppercase">Database Connection</span>
                    <p class="text-xs font-bold text-emerald-400 mt-1 flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                        {{ healthStatus.database }}
                    </p>
                </div>
                <div class="p-4 rounded-2xl bg-slate-950 border border-slate-800">
                    <span class="text-[10px] text-slate-400 font-mono uppercase">QR Engine Security</span>
                    <p class="text-xs font-bold text-brand-lemon mt-1">
                        {{ healthStatus.qr_engine }}
                    </p>
                </div>
                <div class="p-4 rounded-2xl bg-slate-950 border border-slate-800">
                    <span class="text-[10px] text-slate-400 font-mono uppercase">Privacy Firewall</span>
                    <p class="text-xs font-bold text-emerald-400 mt-1">
                        {{ healthStatus.privacy_firewall }}
                    </p>
                </div>
                <div class="p-4 rounded-2xl bg-slate-950 border border-slate-800">
                    <span class="text-[10px] text-slate-400 font-mono uppercase">Runtime Stack</span>
                    <p class="text-xs font-mono font-bold text-slate-300 mt-1">
                        PHP {{ healthStatus.php_version }} • Laravel {{ healthStatus.laravel_version }}
                    </p>
                </div>
            </div>

            <!-- Content Grid: QR Codes Integrity & Audit Trail -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                <!-- QR Tokens Integrity Manager -->
                <div class="lg:col-span-6 bg-slate-950 rounded-3xl border border-slate-800 p-6 shadow-xl space-y-4">
                    <div class="flex justify-between items-center pb-2 border-b border-slate-800">
                        <div>
                            <h3 class="font-bold text-base text-white">Integritas Token QR BioTag</h3>
                            <p class="text-xs text-slate-400">Pencegahan duplikasi dan mekanisme penonaktifan darurat</p>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs">
                            <thead class="bg-slate-900 text-slate-400 font-mono text-[10px] uppercase border-b border-slate-800">
                                <tr>
                                    <th class="p-2.5">Token Acak</th>
                                    <th class="p-2.5">Pohon Terkait</th>
                                    <th class="p-2.5">Status</th>
                                    <th class="p-2.5 text-right">Kontrol</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-800/80 font-mono">
                                <tr v-for="qr in recentQRs" :key="qr.id" class="hover:bg-slate-900/50">
                                    <td class="p-2.5 text-brand-lemon text-[11px] truncate max-w-[140px]">{{ qr.token }}</td>
                                    <td class="p-2.5 text-slate-300 text-[11px]">{{ qr.tree?.tree_code }} ({{ qr.tree?.common_name }})</td>
                                    <td class="p-2.5">
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold" :class="qr.status === 'active' ? 'bg-emerald-950 text-emerald-400 border border-emerald-800' : qr.status === 'used' ? 'bg-amber-950 text-amber-400 border border-amber-800' : 'bg-red-950 text-red-400 border border-red-800'">
                                            {{ qr.status }}
                                        </span>
                                    </td>
                                    <td class="p-2.5 text-right">
                                        <button
                                            @click="toggleQr(qr.id)"
                                            class="px-2 py-1 rounded text-[10px] font-bold transition-colors"
                                            :class="qr.status === 'disabled' ? 'bg-emerald-800 hover:bg-emerald-700 text-white' : 'bg-red-900 hover:bg-red-800 text-red-200'"
                                        >
                                            {{ qr.status === 'disabled' ? 'Aktifkan' : 'Disable' }}
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Audit Logs Stream -->
                <div class="lg:col-span-6 bg-slate-950 rounded-3xl border border-slate-800 p-6 shadow-xl space-y-4">
                    <div class="flex justify-between items-center pb-2 border-b border-slate-800">
                        <div>
                            <h3 class="font-bold text-base text-white">Live Audit Log & Keamanan</h3>
                            <p class="text-xs text-slate-400">Setiap mutasi data kritis tercatat secara immutable</p>
                        </div>
                    </div>

                    <div class="space-y-2 max-h-96 overflow-y-auto pr-1">
                        <div v-for="log in auditLogs" :key="log.id" class="p-3 rounded-2xl bg-slate-900 border border-slate-800/80 font-mono text-xs space-y-1">
                            <div class="flex justify-between items-center text-[10px]">
                                <span class="text-brand-lemon font-bold">[{{ log.action }}]</span>
                                <span class="text-slate-500">{{ log.created_at ? new Date(log.created_at).toLocaleTimeString() : '-' }}</span>
                            </div>
                            <p class="text-slate-300 text-[11px]">
                                Entity: <span class="text-emerald-400">{{ log.entity_type }} #{{ log.entity_id || '-' }}</span>
                                by <span class="text-white font-bold">{{ log.user?.name || 'System' }}</span>
                            </p>
                            <p class="text-[10px] text-slate-500">IP: {{ log.ip_address || '127.0.0.1' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Versioned Emission Factors Reference -->
            <div class="bg-slate-950 rounded-3xl border border-slate-800 p-6 shadow-xl space-y-3">
                <h3 class="font-bold text-base text-white">Faktor Emisi Berstandar (ESDM & IPCC v1.0-2026)</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div v-for="factor in emissionFactors" :key="factor.id" class="p-4 rounded-2xl bg-slate-900 border border-slate-800 text-xs space-y-1 font-mono">
                        <span class="text-[10px] text-brand-lemon uppercase font-bold">{{ factor.category }}</span>
                        <p class="text-lg font-black text-white">{{ factor.co2e_factor }} <span class="text-xs text-slate-400 font-normal">kg/{{ factor.unit }}</span></p>
                        <p class="text-[10px] text-slate-500 truncate">{{ factor.source_reference }}</p>
                    </div>
                </div>
            </div>
        </div>
    </BioTagLayout>
</template>
