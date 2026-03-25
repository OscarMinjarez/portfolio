<script setup lang="ts">
import { ref } from 'vue'
import axios from 'axios'
import { ScrollArea } from '@/components/ui/scroll-area'
import { Input } from '@/components/ui/input'
import { Button } from '@/components/ui/button'

const prompt = ref('')

// Historial inicial con la bienvenida directa
const messages = ref([
    {
        role: 'ari',
        text: 'Qué onda. Soy Ari, la inteligencia detrás de los fierros de Oscar. ¿Vienes a ver su trabajo o tienes una duda técnica directa sobre su stack?',
    },
])

const sendMessage = async () => {
    if (!prompt.value.trim()) return

    const userText = prompt.value
    messages.value.push({ role: 'user', text: userText })
    prompt.value = ''

    try {
        const response = await axios.post('/chat', { prompt: userText })
        messages.value.push({ role: 'ari', text: response.data.reply })
    } catch (error) {
        messages.value.push({
            role: 'ari',
            text: 'Mmm, algo tronó en el servidor. Intenta de nuevo.',
        })
    }
}
</script>

<template>
    <div class="relative w-full flex flex-col h-[500px] border border-border/40 rounded-3xl bg-card/10 shadow-sm overflow-hidden">
        
        <ScrollArea class="flex-1 px-6 pt-6 pb-28">
            <div class="flex flex-col gap-6">
                <div 
                    v-for="(msg, i) in messages" 
                    :key="i" 
                    class="flex gap-4"
                    :class="msg.role === 'user' ? 'flex-row-reverse' : 'flex-row'"
                >
                    <div 
                        class="flex-shrink-0 flex items-center justify-center w-8 h-8 rounded-full border shadow-sm"
                        :class="msg.role === 'ari' ? 'bg-primary/10 border-primary/20 text-primary' : 'bg-muted border-border/50 text-muted-foreground'"
                    >
                        <span class="text-xs font-bold font-mono">{{ msg.role === 'ari' ? 'A' : 'V' }}</span>
                    </div>
                    
                    <div class="flex flex-col gap-1.5 max-w-[80%]" :class="msg.role === 'user' ? 'items-end' : 'items-start'">
                        <span class="text-[11px] font-medium text-muted-foreground uppercase tracking-widest mx-1">
                            {{ msg.role === 'ari' ? 'Ari' : 'Visitante' }}
                        </span>
                        
                        <div 
                            class="px-4 py-3 text-sm leading-relaxed"
                            :class="msg.role === 'user' 
                                ? 'bg-primary text-primary-foreground rounded-2xl rounded-tr-sm' 
                                : 'bg-muted/30 border border-border/40 text-foreground rounded-2xl rounded-tl-sm'"
                        >
                            {{ msg.text }}
                        </div>
                    </div>
                </div>
            </div>
        </ScrollArea>

        <div class="absolute bottom-0 left-0 w-full p-4 bg-gradient-to-t from-background via-background/95 to-transparent">
            <form @submit.prevent="sendMessage" class="relative flex items-center max-w-3xl mx-auto">
                <Input
                    v-model="prompt"
                    placeholder="Pregúntale a Ari..."
                    class="w-full bg-background border border-border/60 rounded-full pl-6 pr-14 h-12 text-sm shadow-md focus-visible:ring-1 focus-visible:ring-primary focus-visible:border-border transition-all"
                />
                <Button
                    type="submit"
                    size="icon"
                    class="absolute right-1.5 top-1/2 -translate-y-1/2 h-9 w-9 rounded-full bg-primary text-primary-foreground shadow-sm hover:bg-primary/90 transition-transform active:scale-95"
                    :disabled="!prompt.trim()"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m5 12 7-7 7 7"/><path d="M12 19V5"/></svg>
                </Button>
            </form>
        </div>
        
    </div>
</template>