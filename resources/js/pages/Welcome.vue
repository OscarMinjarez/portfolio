<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { Card } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import AIChat from '@/components/AIChat.vue';

defineProps<{
    projects: Array<any>;
}>();
</script>

<template>
    <Head title="Oscar Minjarez | Software Engineer" />

    <div class="min-h-screen bg-background text-foreground p-8 md:p-16">
        <div class="max-w-5xl mx-auto space-y-16">
            
            <section>
                <AIChat />
            </section>

            <section id="proyectos">
                <h2 class="text-lg font-medium tracking-tight mb-6">Proyectos Destacados</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <Card 
                        v-for="project in projects" 
                        :key="project.id"
                        class="p-6 hover:bg-muted/10 hover:border-border/80 flex flex-col justify-between transition-all duration-300"
                    >
                        <div>
                            <h3 class="font-medium tracking-tight">{{ project.title }}</h3>
                            <p class="text-sm text-muted-foreground mt-2 leading-relaxed">
                                {{ project.summary }}
                            </p>
                        </div>  
                        
                        <div class="flex flex-wrap gap-2 mt-6">
                            <Badge 
                                v-for="tech in project.stack" 
                                :key="tech" 
                                variant="outline"
                            >
                                {{ tech }}
                            </Badge>
                        </div>
                    </Card>
                </div>
            </section>
        </div>
    </div>
</template>
