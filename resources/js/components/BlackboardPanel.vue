<script setup lang="ts">
import { ref, watch, nextTick } from 'vue';
import { useBlackboard } from '../composables/useBlackboard';
import { useDraggable, useStorage, useElementSize } from '@vueuse/core';
import { Minus, Plus, GripVertical, ChevronRight } from 'lucide-vue-next';

const { logs } = useBlackboard();
const terminalContainer = ref<HTMLElement | null>(null);
const el = ref<HTMLElement | null>(null);
const handle = ref<HTMLElement | null>(null);

const isMinimized = useStorage('blackboard-minimized', false);
const pos = useStorage('blackboard-pos', { x: 24, y: 24 });
const panelSize = useStorage('blackboard-size', { width: 400, height: 320 });

const { width, height } = useElementSize(el);
watch([width, height], ([newW, newH]) => {
    if (!isMinimized.value && newW > 100 && newH > 100) {
        const diffW = Math.abs(newW - panelSize.value.width);
        const diffH = Math.abs(newH - panelSize.value.height);
        
        if (diffW > 5 || diffH > 5) {
            panelSize.value = { width: newW, height: newH };
        }
    }
});

const { x, y, style } = useDraggable(el, {
  initialValue: { x: pos.value.x, y: pos.value.y },
  handle: handle,
  onEnd: (position) => {
    const margin = 50; 
    const maxX = window.innerWidth - margin;
    const maxY = window.innerHeight - margin;
    
    if (position.x < -200 || position.y < 0 || position.x > maxX || position.y > maxY) {
        const randX = 100 + Math.random() * (window.innerWidth - 400);
        const randY = 100 + Math.random() * (window.innerHeight - 300);
        x.value = randX;
        y.value = randY;
        pos.value = { x: randX, y: randY };
    } else {
        pos.value = { x: position.x, y: position.y };
    }
  }
});

watch(logs, async function () {
    if (isMinimized.value) return;
    await nextTick();
    if (terminalContainer.value) {
        terminalContainer.value.scrollTop = terminalContainer.value.scrollHeight;
    }
}, { deep: true });

function getColor(source: string) {
    if (source === 'OBSERVER') return 'text-emerald-400';
    if (source === 'INTENT_RADAR') return 'text-purple-400';
    if (source === 'AGENT_ARI' || source === 'AGENT_NARRATOR') return 'text-amber-400';
    if (source === 'BLACKBOARD') return 'text-emerald-400';
    if (source === 'SYSTEM_ERROR') return 'text-red-400';
    return 'text-slate-400';
};
</script>

<template>
    <div 
        ref="el"
        :style="[
            style, 
            !isMinimized 
                ? { width: `${panelSize.width}px`, height: `${panelSize.height}px` } 
                : { width: '200px', height: 'auto' }
        ]"
        :class="[
            'fixed z-[150] overflow-hidden flex flex-col shadow-2xl transition-shadow duration-300',
            'bg-card/70 dark:bg-slate-950/75 backdrop-blur-2xl border border-border/50 rounded-lg',
            !isMinimized ? 'resize both min-w-[280px] min-h-[150px]' : 'pointer-events-auto resize-none'
        ]"
    >
        <!-- Header / Drag Handle -->
        <div 
            ref="handle"
            class="bg-muted/80 px-3 py-2 border-b border-border flex justify-between items-center cursor-grab active:cursor-grabbing select-none group shrink-0"
        >
            <div class="flex items-center gap-2 overflow-hidden">
                <GripVertical class="w-3 h-3 text-muted-foreground opacity-30 group-hover:opacity-100 transition-opacity" />
                <span class="text-[9px] text-primary/80 font-mono tracking-widest uppercase font-bold truncate">[- Sistema Nervioso Central -]</span>
            </div>
            
            <div class="flex items-center gap-2">
                <div class="flex gap-1 items-center mr-2">
                    <div class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></div>
                    <span class="text-[8px] text-green-500/80 font-mono hidden sm:inline">LIVE</span>
                </div>
                <button 
                    @click="isMinimized = !isMinimized"
                    class="p-1 hover:bg-foreground/10 rounded transition-colors text-muted-foreground"
                >
                    <Minus v-if="!isMinimized" class="w-3 h-3" />
                    <Plus v-else class="w-3 h-3" />
                </button>
            </div>
        </div>

        <!-- Content Area -->
        <div 
            v-show="!isMinimized"
            ref="terminalContainer" 
            class="p-4 flex-1 overflow-y-auto font-mono text-[11px] leading-[1.65] space-y-4 scroll-smooth tracking-normal terminal-text"
        >
            <div v-if="logs.length === 0" class="text-muted-foreground/40 italic">Esperando estímulos de la matriz...</div>
            
            <div v-for="(log, index) in logs" :key="index" class="flex items-start gap-2 break-words text-foreground/90 dark:text-slate-300">
                <span class="text-muted-foreground shrink-0 opacity-40 font-light font-mono text-[10px]">[{{ log.time }}]</span>
                <span :class="['font-bold shrink-0 uppercase text-[10px]', getColor(log.source)]">{{ log.source.replace('AGENT_ARI', 'ARI').replace('SYSTEM_ERROR', 'ERROR') }}:</span>
                <span class="flex-1">{{ log.msg }}</span>
            </div>
        </div>

        <!-- Resize visual indicator -->
        <div v-if="!isMinimized" class="absolute bottom-0 right-0 w-4 h-4 pointer-events-none opacity-20 group-hover:opacity-100 transition-opacity">
            <ChevronRight class="w-2 h-2 absolute bottom-0.5 right-0.5 rotate-45 text-muted-foreground" />
        </div>

        <!-- Minimized State Hint -->
        <div v-if="isMinimized" class="px-3 py-1 bg-primary/5 text-[9px] text-center text-muted-foreground font-mono italic">
            Monitor sordeado...
        </div>

    </div>
</template>

<style scoped>
::selection {
    background: var(--primary);
    color: var(--primary-foreground);
}

.terminal-text {
    font-family: 'Geist Mono', 'JetBrains Mono', 'Fira Code', 'Cascadia Code', ui-monospace, monospace;
}

::-webkit-scrollbar {
    width: 3px;
}
::-webkit-scrollbar-track {
    background: transparent;
}
::-webkit-scrollbar-thumb {
    background: var(--primary);
    border-radius: 10px;
}
</style>
