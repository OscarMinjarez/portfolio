<script setup lang="ts">
import { ref, watch, nextTick } from 'vue';
import { useBlackboard } from '../composables/useBlackboard';

const { logs } = useBlackboard();
const terminalContainer = ref<HTMLElement | null>(null);

watch(logs, async function () {
    await nextTick();
    if (terminalContainer.value) {
        terminalContainer.value.scrollTop = terminalContainer.value.scrollHeight;
    }
}, { deep: true });

function getColor(source: string) {
    if (source === 'OBSERVER') return 'text-blue-400';
    if (source === 'INTENT_RADAR') return 'text-purple-400';
    if (source === 'AGENT') return 'text-yellow-400';
    if (source === 'SYSTEM') return 'text-green-400';
    return 'text-slate-300';
};
</script>

<template>
    <div class="fixed top-6 left-6 w-[400px] bg-slate-950/90 backdrop-blur-md border border-slate-800 rounded-lg shadow-2xl z-50 overflow-hidden flex flex-col pointer-events-none">
        
        <div class="bg-slate-900 px-4 py-2 border-b border-slate-800 flex justify-between items-center">
            <span class="text-xs text-slate-400 font-mono tracking-widest uppercase">[- Sistema Nervioso Central -]</span>
            <div class="flex gap-1.5">
                <div class="w-2 h-2 rounded-full bg-slate-700"></div>
                <div class="w-2 h-2 rounded-full bg-slate-700"></div>
                <div class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></div>
            </div>
        </div>

        <div ref="terminalContainer" class="p-4 h-64 overflow-y-auto font-mono text-[11px] leading-relaxed space-y-2 pointer-events-auto scroll-smooth">
            <div v-if="logs.length === 0" class="text-slate-600 italic">Esperando estímulos de navegación...</div>
            
            <div v-for="(log, index) in logs" :key="index" class="flex items-start gap-2 break-words">
                <span class="text-slate-500 shrink-0">[{{ log.time }}]</span>
                <span :class="['font-semibold shrink-0', getColor(log.source)]">{{ log.source }}:</span>
                <span class="text-slate-300">{{ log.msg }}</span>
            </div>
        </div>

    </div>
</template>

<style scoped>
::-webkit-scrollbar {
    width: 4px;
}
::-webkit-scrollbar-track {
    background: transparent;
}
::-webkit-scrollbar-thumb {
    background: #334155;
    border-radius: 4px;
}
</style>