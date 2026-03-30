<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import AdeniumArt from '@/components/AdeniumArt.vue';
import BlackboardPanel from '@/components/BlackboardPanel.vue';
</script>

<template>
    <Head title="Oscar Minjarez | Software Engineer" />

    <BlackboardPanel />

    <div class="min-h-screen bg-slate-950 text-slate-200 p-8 md:p-16 relative overflow-hidden">
        <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-16 relative z-10">

            <div class="space-y-16 pt-24 pb-96">
                <div class="space-y-4">
                    <h1 class="text-4xl font-bold text-white tracking-tight">Oscar Minjarez</h1>
                    <p class="text-slate-400 font-mono text-sm">Ingeniero de Software | Arquitectura AI & Backend</p>
                </div>

                <div class="grid gap-6">
                    <div 
                        v-track="{ name: 'Zanate Nunchi' }" 
                        v-dwell="{ name: 'Stack AI/Java', time: 2000, tech: 'ai' }"
                        class="p-6 border border-slate-800 rounded-xl bg-slate-900/40 hover:bg-slate-800/60 transition-colors backdrop-blur-sm cursor-default"
                    >
                        <h3 class="text-xl font-semibold text-white mb-2">Zanate Nunchi</h3>
                        <p class="text-sm text-slate-400">Mod de Minecraft con Ollama y arquitectura de Pizarra (Blackboard).</p>
                    </div>

                    <div 
                        v-track="{ name: 'Experiencia: Erus' }" 
                        v-dwell="{ name: 'Stack Laravel/Angular', time: 2000, tech: 'backend' }"
                        class="p-6 border border-slate-800 rounded-xl bg-slate-900/40 hover:bg-slate-800/60 transition-colors backdrop-blur-sm mt-64"
                    >
                        <h3 class="text-xl font-semibold text-white mb-2">Full Stack en Erus</h3>
                        <p class="text-sm text-slate-400">Mantenimiento integral de módulos críticos ERP/SaaS con Laravel y Angular.</p>
                    </div>
                </div>
            </div>

            <div class="relative hidden lg:flex items-start justify-center pt-24">
                <div class="sticky top-32">
                    <AdeniumArt />
                </div>
            </div>

        </div>
    </div>
</template>