<script setup lang="ts">
import { computed, onMounted, ref } from 'vue';
import { Head } from '@inertiajs/vue3';
import { driver } from 'driver.js';
import 'driver.js/dist/driver.css';

import Navbar from '@/components/Navbar.vue';
import AdeniumArt from '@/components/AdeniumArt.vue';
import BlackboardPanel from '@/components/BlackboardPanel.vue';
import useAgent from '@/composables/useAgent';
import { useBlackboard } from '@/composables/useBlackboard';

const { askDetails, askWelcome } = useAgent();
const { isMinimized } = useBlackboard();

const props = defineProps<{
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

const mixedItems = computed(function () {
    const items: any[] = [];
    props.work_experiences?.forEach(job => items.push({ type: 'job', data: job }));
    props.projects?.forEach(proj => items.push({ type: 'project', data: proj }));
    return items;
});

function getCardClass() {
    return 'break-inside-avoid relative w-full mb-6 transition-all duration-300 ease-out hover:z-10 hover:-translate-y-1 hover:shadow-xl hover:scale-[1.02] group rounded-2xl';
};

function startTour() {
    const driverObj = driver({
        showProgress: true,
        animate: true,
        nextBtnText: 'Siguiente &rarr;',
        prevBtnText: '&larr; Atrás',
        doneBtnText: 'Entendido',
        stagePadding: 12,
        popoverClass: 'ari-tour-theme',
        steps: [
            { 
                popover: { 
                    title: 'Portafolio de Oscar', 
                    description: 'Esto no es un currículum estático. Estás a punto de interactuar con una arquitectura conectada a la base de datos central.' 
                } 
            },
            { 
                element: '#monitor-ia',
                popover: { 
                    title: 'Panel de Ari', 
                    description: 'Conoce a Ari, mi asistente personal de IA. Ella te acompañará mientras exploras. Cuando quieras leer después, puedes minimizarla y regresará a ser una notificación en la esquina.',
                    side: "bottom", 
                    align: "start"
                } 
            },
            { 
                element: '.card-scatter > div', 
                popover: { 
                    title: 'Inicia el análisis', 
                    description: 'Deja tu cursor sobre cualquier tarjeta por 3 segundos para que el agente extraiga el reporte. Dale clic si quieres ver los detalles a fondo.',
                    side: "right",
                    align: "center"
                } 
            }
        ],
        onPopoverRender: (popover: any) => {
            if (driverObj.getActiveIndex() === 1) {
                isMinimized.value = false;
            }
        },
        onDestroyStarted: () => {
            localStorage.setItem('tour_visto', 'true');
            driverObj.destroy();
            isMinimized.value = true;
            setTimeout(() => askWelcome(), 500);
        }
    });
    driverObj.drive();
};

onMounted(() => {
    if (!localStorage.getItem('tour_visto')) {
        setTimeout(() => startTour(), 1000);
    } else {
        setTimeout(() => askWelcome(), 1000);
    }
});
</script>

<template>
    <Head title="Oscar Minjarez | Software Engineer" />

    <Navbar />
    <BlackboardPanel />

    <div class="min-h-screen w-full bg-background text-foreground relative transition-colors duration-500 flex flex-col lg:flex-row">
        
        <div class="fixed top-0 left-0 w-full h-full pointer-events-none opacity-20 dark:opacity-10 z-0">
            <div class="absolute -top-[10%] -left-[10%] w-[40%] h-[40%] bg-blue-500/20 blur-[120px] rounded-full"></div>
            <div class="absolute -bottom-[10%] -right-[10%] w-[40%] h-[40%] bg-purple-500/20 blur-[120px] rounded-full"></div>
        </div>

        <!-- Móvil: Art section -->
        <div class="w-full lg:hidden pt-36 px-6 flex justify-center relative z-20">
            <div class="w-2/3 max-w-[240px]">
                <AdeniumArt />
            </div>
        </div>

        <div class="w-full lg:w-[50%] xl:w-[55%] pt-10 px-6 lg:pl-12 lg:pr-8 relative z-20">
            <div class="min-h-screen pt-24 lg:pt-32 pb-32 columns-1 xl:columns-2 gap-6 w-full max-w-5xl mx-auto card-scatter">
                <div 
                    v-for="(item, index) in mixedItems" 
                    :key="index"
                    :class="getCardClass()"
                >
                    <!-- Project Card -->
                    <div 
                        v-if="item.type === 'project'"
                        @click="askDetails(item.data.title)"
                        v-track="{ name: item.data.title }" 
                        v-dwell="{ name: item.data.title, time: 3000, tech: item.data.stack[0] || 'general' }"
                        class="h-full p-6 md:p-7 border border-border/50 dark:border-primary/20 rounded-2xl bg-card hover:border-primary/50 dark:hover:border-primary transition-all shadow-lg hover:shadow-primary/10 dark:shadow-black/20 cursor-pointer outline-1 outline-transparent group/card"
                    >
                    <h3 class="text-xl font-bold mb-3 text-foreground group-hover/card:text-primary transition-colors">{{ item.data.title }}</h3>
                    <p class="text-sm text-foreground/80 dark:text-muted-foreground mb-5 leading-relaxed line-clamp-3">{{ item.data.summary }}</p>
                    
                    <div class="flex flex-wrap gap-2">
                        <span 
                            v-for="tech in item.data.stack.slice(0, 3)" 
                            :key="tech" 
                            class="px-2.5 py-1 text-[10px] md:text-xs font-mono font-medium uppercase tracking-wider rounded-md border border-border/50 dark:border-primary/20 bg-secondary/50 text-secondary-foreground"
                        >
                            {{ tech }}
                        </span>
                        <span v-if="item.data.stack.length > 3" class="px-2.5 py-1 text-[10px] md:text-xs font-mono font-medium text-muted-foreground flex items-center">
                            +{{ item.data.stack.length - 3 }}
                        </span>
                    </div>
                    </div>

                    <!-- Experience Card -->
                    <div 
                        v-else
                        @click="askDetails(item.data.company)"
                        v-track="{ name: item.data.company }" 
                        v-dwell="{ name: item.data.company, time: 3000, tech: 'backend' }"
                        class="h-full p-6 md:p-7 border border-border/50 dark:border-primary/20 rounded-2xl bg-card hover:border-primary/50 dark:hover:border-primary transition-all shadow-lg hover:shadow-primary/10 dark:shadow-black/20 cursor-pointer outline-1 outline-transparent group/card"
                    >
                    <h3 class="text-xl font-bold text-foreground mb-1 group-hover/card:text-primary transition-colors">{{ item.data.position }}</h3>
                    <div class="flex items-center gap-2 mb-4">
                        <span class="text-sm text-primary font-medium">{{ item.data.company }}</span>
                        <span class="w-1 h-1 rounded-full bg-border"></span>
                        <span class="text-[11px] md:text-xs text-muted-foreground font-mono font-medium">{{ item.data.start_date.substring(0,4) }} - {{ item.data.end_date ? item.data.end_date.substring(0,4) : 'Presente' }}</span>
                    </div>
                        <p class="text-sm text-foreground/80 dark:text-muted-foreground leading-relaxed line-clamp-3">{{ item.data.description }}</p>
                    </div>
                </div>
            </div>
            <!-- Espaciador final para scroll móvil -->
            <div class="h-20 lg:hidden"></div>
        </div>

        <!-- Escritorio: Art section -->
        <div class="fixed right-0 top-[10vh] xl:top-0 w-full lg:w-[50%] xl:w-[45%] h-full hidden lg:flex flex-col items-center justify-center pointer-events-none z-10 pb-16 xl:pb-0">
            <div class="transform scale-[1.6] xl:scale-[2.2] 2xl:scale-[2.5] origin-center pointer-events-auto">
                <AdeniumArt />
            </div>
        </div>

    </div>
</template>

<style scoped>
/* Eliminated standard blur */
</style>