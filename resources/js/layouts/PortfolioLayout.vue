<script setup lang="ts">
import { Link } from '@inertiajs/vue3'
// Si después instalas un paquete de iconos (como lucide-vue-next), aquí los importas
</script>

<template>
    <div class="min-h-screen bg-background text-foreground selection:bg-primary selection:text-primary-foreground">
        <header class="sticky top-0 z-40 w-full backdrop-blur-md bg-background/70 border-b border-border/40">
            <div class="max-w-5xl mx-auto flex h-14 items-center justify-between px-8 md:px-16">
                <div class="flex items-center gap-6">
                    <Link href="/" class="font-semibold tracking-tight text-foreground flex items-center gap-2">
                        <span class="text-xl text-muted-foreground">~</span>
                        <span>oscar<span class="text-muted-foreground">minjarez</span></span>
                    </Link>
                </div>

                <nav class="flex items-center gap-6 text-sm font-medium text-muted-foreground">
                    <a href="#proyectos" class="hover:text-foreground transition-colors">Proyectos</a>
                    <a href="#experiencia" class="hover:text-foreground transition-colors">Experiencia</a>
                    <a href="https://github.com/oscarminjarez" target="_blank" class="hover:text-foreground transition-colors">
                        GitHub
                    </a>
                </nav>
            </div>
        </header>

        <main class="flex-1">
            <slot />
        </main>

        <footer class="border-t border-border/40 py-8 text-sm text-muted-foreground">
            <div class="max-w-5xl mx-auto px-8 md:px-16 flex flex-col md:flex-row justify-between items-center gap-4">
                <p>© {{ new Date().getFullYear() }} Oscar Minjarez. Todos los sistemas en línea.</p>
                <p>Construido con <span class="text-foreground font-medium">Laravel 13</span> & <span class="text-foreground font-medium">Vue</span></p>
            </div>
        </footer>
    </div>
</template>