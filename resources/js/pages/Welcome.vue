<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import AdeniumArt from '@/components/AdeniumArt.vue';
import BlackboardPanel from '@/components/BlackboardPanel.vue';

defineProps<{
    projects: Array<{
        id: number;
        title: string;
        slug: string;
        summary: string;
        stack: string[];
    }>;
    work_experiences: Array<{
        id: number;
        company: string;
        position: string;
        start_date: string;
        end_date: string | null;
        description: string;
    }>;
}>();
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

                <div v-if="work_experiences && work_experiences.length > 0" class="space-y-6">
                    <h2 class="text-2xl font-bold text-white/80 border-b border-slate-800 pb-2">Trayectoria</h2>
                    <div 
                        v-for="job in work_experiences" 
                        :key="job.id"
                        v-track="{ name: job.company }" 
                        v-dwell="{ name: job.company, time: 3000, tech: 'backend' }"
                        class="p-6 border border-slate-800 rounded-xl bg-slate-900/40 hover:bg-slate-800/60 transition-colors backdrop-blur-sm cursor-default"
                    >
                        <h3 class="text-xl font-semibold text-white">{{ job.position }}</h3>
                        <p class="text-sm text-blue-400 font-medium mb-3">{{ job.company }} <span class="text-slate-600 ml-2">| {{ job.start_date.substring(0,4) }} - {{ job.end_date ? job.end_date.substring(0,4) : 'Presente' }}</span></p>
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