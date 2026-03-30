<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import AdeniumArt from '@/components/AdeniumArt.vue';
</script>

<template>
    <Head title="Oscar Minjarez | Software Engineer" />

    <div class="min-h-screen bg-background text-foreground p-8 md:p-16">
        <div class="max-w-5xl mx-auto space-y-16">

            <div class="grid gap-4">
                <div v-track="{ name: 'Zanate Nunchi' }" class="p-4 border rounded-xl bg-slate-900">
                    <h3>Zanate Nunchi</h3>
                    <p>Mod de Minecraft con Ollama...</p>
                </div>

                <div v-track="{ name: 'Experiencia: Erus' }" class="p-4 border rounded-xl bg-slate-900 mt-96">
                    <h3>Full Stack en Erus</h3>
                    <p>Mantenimiento integral de módulos críticos...</p>
                </div>
            </div>

            <section>
                <AdeniumArt />
            </section>
        </div>
    </div>
</template>
