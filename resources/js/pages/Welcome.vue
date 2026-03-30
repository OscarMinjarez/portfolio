<script setup lang="ts">
import { computed, ref } from 'vue';
import { Head } from '@inertiajs/vue3';
import Navbar from '@/components/Navbar.vue';
import AdeniumArt from '@/components/AdeniumArt.vue';
import BlackboardPanel from '@/components/BlackboardPanel.vue';

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

const itemStyles = ref<any[]>([]);

const scatteredItems = computed(function () {
    const items: any[] = [];
    props.work_experiences?.forEach(job => items.push({ type: 'job', data: job }));
    props.projects?.forEach(proj => items.push({ type: 'project', data: proj }));
    
    return items.map((item, index) => {
        if (!itemStyles.value[index]) {
            let left = 0;
            let top = 0;
            let attempts = 0;
            let isOverlapping = true;
            const marginW = 16; 
            const marginH = 14;
            while (isOverlapping && attempts < 50) {
                left = 3 + (Math.random() * 50); 
                top = 16 + (Math.random() * 64);
                isOverlapping = false;
                for (let i = 0; i < index; i++) {
                    const prev = itemStyles.value[i];
                    if (!prev) continue;
                    const prevLeft = parseFloat(prev.left);
                    const prevTop = parseFloat(prev.top);
                    const diffX = Math.abs(left - prevLeft);
                    const diffY = Math.abs(top - prevTop);
                    if (diffX < marginW && diffY < marginH) {
                        isOverlapping = true;
                        break;
                    }
                }
                attempts++;
            }
            const rotation = (Math.random() * 6) - 3;
            itemStyles.value[index] = {
                left: `${left}%`,
                top: `${top}%`,
                '--rotate-angle': `${rotation}deg`,
                zIndex: 10 + index
            };
        }
        return {
            ...item,
            style: itemStyles.value[index]
        };
    });
});

function getScatterClass() {
    return 'lg:absolute relative w-full lg:w-[320px] xl:w-[360px] mb-6 lg:mb-0 transition-all duration-700 ease-out hover:!z-[100] lg:hover:-translate-y-2 lg:hover:scale-105 group card-scatter';
};
</script>

<template>
    <Head title="Oscar Minjarez | Software Engineer" />

    <Navbar />
    <BlackboardPanel />

    <div class="h-screen w-full bg-background text-foreground relative overflow-hidden transition-colors duration-500 flex flex-col lg:flex-row">
        
        <div class="absolute top-0 left-0 w-full h-full pointer-events-none opacity-20 dark:opacity-10 z-0">
            <div class="absolute -top-[10%] -left-[10%] w-[40%] h-[40%] bg-blue-500/20 blur-[120px] rounded-full"></div>
            <div class="absolute -bottom-[10%] -right-[10%] w-[40%] h-[40%] bg-purple-500/20 blur-[120px] rounded-full"></div>
        </div>

        <!-- Móvil: Art section -->
        <div class="w-full lg:hidden pt-36 px-6 flex justify-center relative z-20">
            <div class="w-2/3 max-w-[240px]">
                <AdeniumArt />
            </div>
        </div>

        <div class="w-full lg:w-[60%] h-full pt-12 lg:pt-0 px-6 lg:px-0 overflow-y-auto lg:overflow-visible relative z-20">
            <div 
                v-for="(item, index) in scatteredItems" 
                :key="index"
                :class="getScatterClass()"
                :style="item.style"
            >
                <!-- Project Card -->
                <div 
                    v-if="item.type === 'project'"
                    v-track="{ name: item.data.title }" 
                    v-dwell="{ name: item.data.title, time: 3000, tech: item.data.stack[0] || 'general' }"
                    class="p-5 border border-border/50 dark:border-primary/20 rounded-2xl bg-card hover:border-primary/50 dark:hover:border-primary transition-all shadow-lg hover:shadow-primary/10 dark:shadow-black/20 cursor-pointer outline-1 outline-transparent group/card"
                >
                    <h3 class="text-lg font-semibold mb-2 text-foreground group-hover/card:text-primary transition-colors">{{ item.data.title }}</h3>
                    <p class="text-xs text-muted-foreground mb-4 leading-relaxed line-clamp-3">{{ item.data.summary }}</p>
                    
                    <div class="flex flex-wrap gap-1.5">
                        <span 
                            v-for="tech in item.data.stack.slice(0, 3)" 
                            :key="tech" 
                            class="px-2 py-0.5 text-[9px] font-mono uppercase tracking-wider rounded border border-border/50 dark:border-primary/20 bg-secondary/30 text-secondary-foreground"
                        >
                            {{ tech }}
                        </span>
                        <span v-if="item.data.stack.length > 3" class="px-2 py-0.5 text-[9px] font-mono text-muted-foreground">
                            +{{ item.data.stack.length - 3 }}
                        </span>
                    </div>
                </div>

                <!-- Experience Card -->
                <div 
                    v-else
                    v-track="{ name: item.data.company }" 
                    v-dwell="{ name: item.data.company, time: 3000, tech: 'backend' }"
                    class="p-5 border border-border/50 dark:border-primary/20 rounded-2xl bg-card hover:border-primary/50 dark:hover:border-primary transition-all shadow-lg hover:shadow-primary/10 dark:shadow-black/20 cursor-pointer outline-1 outline-transparent group/card"
                >
                    <h3 class="text-lg font-semibold text-foreground mb-1 group-hover/card:text-primary transition-colors">{{ item.data.position }}</h3>
                    <div class="flex items-center gap-2 mb-3">
                        <span class="text-xs text-primary font-medium">{{ item.data.company }}</span>
                        <span class="w-1 h-1 rounded-full bg-border"></span>
                        <span class="text-[10px] text-muted-foreground font-mono">{{ item.data.start_date.substring(0,4) }} - {{ item.data.end_date ? item.data.end_date.substring(0,4) : 'Presente' }}</span>
                    </div>
                    <p class="text-xs text-muted-foreground leading-relaxed line-clamp-3">{{ item.data.description }}</p>
                </div>
            </div>
            <!-- Espaciador final para scroll móvil -->
            <div class="h-20 lg:hidden"></div>
        </div>

        <!-- Escritorio: Art section -->
        <div class="absolute right-0 top-0 w-full lg:w-[45%] h-full hidden lg:flex items-center justify-center pointer-events-none z-10">
            <div class="transform scale-150 xl:scale-[2.2] origin-center pointer-events-auto">
                <AdeniumArt />
            </div>
        </div>

    </div>
</template>

<style scoped>
.card-scatter {
    rotate: var(--rotate-angle, 0deg);
    backface-visibility: hidden;
    -webkit-backface-visibility: hidden;
    transform-style: preserve-3d;
    will-change: transform, rotate;
    /* Optimización de nitidez extrema */
    filter: blur(0);
    perspective: 1000px;
    image-rendering: -webkit-optimize-contrast;
    transform: translateZ(0);
}

.card-scatter:hover {
    rotate: 0deg;
}

@media (max-width: 1024px) {
    .card-scatter {
        rotate: 0deg !important;
    }
}
</style>