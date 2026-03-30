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

const links = [
    { name: 'Inicio', href: '#inicio' },
    { name: 'Proyectos', href: '#proyectos' },
    { name: 'Stack', href: '#stack' },
    { name: 'Contacto', href: '#contacto' },
];
</script>

<template>
    <nav 
        :class="[
            'fixed top-0 left-0 w-full z-[100] transition-all duration-300 px-6 py-4 md:px-12 md:py-6 flex justify-between items-center',
            isScrolled ? 'bg-background/80 backdrop-blur-md border-b border-white/5 py-3 md:py-4' : 'bg-transparent'
        ]"
    >
        <!-- Identity -->
        <div class="flex flex-col">
            <span class="text-lg md:text-xl font-bold tracking-tight text-foreground/90">Oscar Minjarez</span>
            <span class="text-[9px] md:text-[10px] text-primary font-mono uppercase tracking-[0.2em] font-medium leading-none mt-0.5">Software Engineer • AI & Backend</span>
        </div>

        <!-- Links & Theme -->
        <div class="hidden md:flex items-center gap-10">
            <div class="flex items-center gap-8">
                <a 
                    v-for="link in links" 
                    :key="link.name" 
                    :href="link.href"
                    class="text-[11px] font-mono uppercase tracking-widest text-muted-foreground hover:text-primary transition-colors relative group"
                >
                    {{ link.name }}
                    <span class="absolute -bottom-1 left-0 w-0 h-px bg-primary transition-all duration-300 group-hover:w-full"></span>
                </a>
            </div>
            
            <div class="h-4 w-px bg-border/40"></div>

            <AppearanceTabs />
        </div>

        <!-- Mobile: Theme toggle only -->
        <div class="md:hidden">
            <AppearanceTabs />
        </div>

    </nav>
</template>
