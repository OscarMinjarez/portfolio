<script setup lang="ts">
import { ref, watch, nextTick } from 'vue';
import { useBlackboard } from '../composables/useBlackboard';
import { Minus, Plus, MessageSquare } from 'lucide-vue-next';

const { logs, isMinimized, unreadCount } = useBlackboard();
const terminalContainer = ref<HTMLElement | null>(null);

watch(logs, async function () {
    if (isMinimized.value) return;
    await nextTick();
    if (terminalContainer.value) {
        terminalContainer.value.scrollTop = terminalContainer.value.scrollHeight;
    }
}, { deep: true });

function getColor(source: string) {
    if (source === 'OBSERVER') return 'text-emerald-400';
    if (source === 'HOVER_EVENT') return 'text-purple-400';
    if (source === 'USER_ACTION') return 'text-emerald-400';
    if (source === 'SYSTEM_HINT') return 'text-blue-400';
    if (source === 'AGENT_ARI' || source === 'AGENT_NARRATOR') return 'text-amber-400';
    if (source === 'BLACKBOARD') return 'text-emerald-400';
    if (source === 'SYSTEM_ERROR') return 'text-red-400';
    return 'text-cyan-400';
};
</script>

<template>
    <div id="monitor-ia" class="fixed bottom-4 right-4 z-150 flex flex-col items-end gap-3 pointer-events-none">
        
        <transition 
            enter-active-class="transition-all duration-300 ease-out"
            enter-from-class="opacity-0 translate-y-4 scale-95"
            enter-to-class="opacity-100 translate-y-0 scale-100"
            leave-active-class="transition-all duration-200 ease-in"
            leave-from-class="opacity-100 translate-y-0 scale-100"
            leave-to-class="opacity-0 translate-y-4 scale-95"
        >
            <div 
                v-if="!isMinimized"
                class="pointer-events-auto bg-card/85 dark:bg-slate-950/90 backdrop-blur-xl border border-border/50 rounded-xl shadow-2xl flex flex-col w-[350px] sm:w-[500px] h-[500px] sm:h-[600px] overflow-hidden"
            >
                <div class="bg-muted/50 px-3 py-2 border-b border-border/50 flex justify-between items-center select-none shrink-0">
                    <div class="flex items-center gap-2">
                        <span class="text-[10px] text-primary/80 font-mono tracking-widest uppercase font-bold">[ Asistente Ari ]</span>
                    </div>
                    
                    <div class="flex items-center gap-2">
                        <div class="flex gap-1 items-center mr-2">
                            <div class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></div>
                            <span class="text-[8px] text-green-500/80 font-mono hidden sm:inline">LIVE</span>
                        </div>
                        <button 
                            @click="isMinimized = true"
                            class="p-1.5 hover:bg-foreground/10 rounded-md transition-colors text-muted-foreground"
                        >
                            <Minus class="w-3 h-3" />
                        </button>
                    </div>
                </div>

                <div 
                    ref="terminalContainer" 
                    class="p-4 flex-1 overflow-y-auto font-mono text-sm leading-relaxed space-y-4 scroll-smooth tracking-normal terminal-text"
                >
                    <div v-if="logs.length === 0" class="text-muted-foreground/40 italic">Esperando estímulos de la matriz...</div>
                    
                    <div v-for="(log, index) in logs" :key="index" class="flex flex-col sm:flex-row sm:items-start gap-1 sm:gap-2 break-words text-foreground/90 dark:text-slate-300">
                        <div class="flex items-center gap-2 shrink-0 sm:pt-1">
                            <span class="text-muted-foreground opacity-50 font-light font-mono text-[11px]">[{{ log.time }}]</span>
                            <span :class="['font-bold uppercase text-xs', getColor(log.source)]">{{ log.source.replace('AGENT_ARI', 'ARI').replace('SYSTEM_ERROR', 'ERROR') }}:</span>
                        </div>
                        <span class="flex-1 mt-1 sm:mt-0 text-[13px] md:text-sm font-medium">{{ log.msg }}</span>
                    </div>
                </div>
            </div>
        </transition>

        <button
            v-if="isMinimized"
            @click="isMinimized = false"
            class="pointer-events-auto w-14 h-14 bg-primary text-primary-foreground rounded-full shadow-xl shadow-primary/20 flex items-center justify-center hover:scale-110 active:scale-95 transition-all relative group"
        >
            <MessageSquare class="w-6 h-6 fill-primary-foreground/20 group-hover:fill-primary-foreground/40 transition-colors" />
            
            <span 
                v-if="unreadCount > 0"
                class="absolute -top-1 -right-1 bg-red-500 text-white text-[10px] font-bold w-6 h-6 flex items-center justify-center rounded-full shadow-md animate-in zoom-in"
            >
                {{ unreadCount > 9 ? '9+' : unreadCount }}
            </span>
        </button>
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
