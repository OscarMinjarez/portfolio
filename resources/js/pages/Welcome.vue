<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { Card } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import AIChat from '@/components/AIChat.vue';
</script>

<template>
    <Head title="Oscar Minjarez | Software Engineer" />

    <div class="min-h-screen bg-background text-foreground p-8 md:p-16">
        <div class="max-w-5xl mx-auto space-y-16">
            
            <section>
                <AIChat />
            </section>
        </div>
    </div>
</template>
