<script setup lang="ts">
import { ref, onMounted, onUnmounted } from 'vue';
import AppearanceTabs from './AppearanceTabs.vue';

const isScrolled = ref(false);

const handleScroll = () => {
    isScrolled.value = window.scrollY > 20;
};

onMounted(() => {
    window.addEventListener('scroll', handleScroll);
});

onUnmounted(() => {
    window.removeEventListener('scroll', handleScroll);
});
</script>

<template>
    <nav 
        :class="[
            'fixed top-0 left-0 w-full z-100 transition-all duration-300 px-6 py-4 md:px-12 md:py-6 flex justify-between items-center',
            isScrolled ? 'bg-background/80 backdrop-blur-md border-b border-border/50 py-3 md:py-4 shadow-sm' : 'bg-transparent'
        ]"
    >
        <!-- Identity -->
        <div class="flex flex-col">
            <span class="text-xl md:text-2xl font-bold tracking-tight text-foreground/90">Oscar Minjarez</span>
            <span class="text-[10px] md:text-xs text-primary font-mono uppercase tracking-[0.2em] font-medium leading-none mt-1">Software Engineer • AI & Backend</span>
        </div>

        <!-- Theme Toggle -->
        <div class="flex items-center gap-4">
            <AppearanceTabs />
        </div>

    </nav>
</template>
