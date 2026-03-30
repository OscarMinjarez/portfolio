<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import AdeniumArt from '@/components/AdeniumArt.vue';
import BlackboardPanel from '@/components/BlackboardPanel.vue';
import AppearanceTabs from '@/components/AppearanceTabs.vue';

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

    <div class="fixed top-6 right-6 z-50">
        <AppearanceTabs />
    </div>

    <div class="min-h-screen bg-background text-foreground p-8 md:p-16 relative overflow-hidden transition-colors duration-500">
        <!-- Subtle Background Deco -->
        <div class="absolute top-0 left-0 w-full h-full pointer-events-none opacity-20 dark:opacity-10">
            <div class="absolute -top-[10%] -left-[10%] w-[40%] h-[40%] bg-blue-500/20 blur-[120px] rounded-full"></div>
            <div class="absolute -bottom-[10%] -right-[10%] w-[40%] h-[40%] bg-purple-500/20 blur-[120px] rounded-full"></div>
        </div>

        <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-16 relative z-10">

            <div class="space-y-16 pt-24 pb-96">
                <div class="space-y-4">
                    <h1 class="text-4xl font-bold tracking-tight">Oscar Minjarez</h1>
                    <p class="text-muted-foreground font-mono text-sm">Ingeniero de Software | Arquitectura AI & Backend</p>
                </div>

                <div v-if="work_experiences && work_experiences.length > 0" class="space-y-6">
                    <h2 class="text-2xl font-bold border-b border-border pb-2 opacity-80">Trayectoria</h2>
                    <div 
                        v-for="job in work_experiences" 
                        :key="job.id"
                        v-track="{ name: job.company }" 
                        v-dwell="{ name: job.company, time: 3000, tech: 'backend' }"
                        class="p-6 border border-border rounded-xl bg-card/40 hover:bg-accent/10 transition-all backdrop-blur-sm cursor-default group"
                    >
                        <h3 class="text-xl font-semibold group-hover:text-primary transition-colors">{{ job.position }}</h3>
                        <p class="text-sm text-blue-500 dark:text-blue-400 font-medium mb-3">
                            {{ job.company }} 
                            <span class="text-muted-foreground ml-2">| {{ job.start_date.substring(0,4) }} - {{ job.end_date ? job.end_date.substring(0,4) : 'Presente' }}</span>
                        </p>
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