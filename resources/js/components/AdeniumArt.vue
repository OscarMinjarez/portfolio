<script setup lang="ts">
import { ref, computed, onMounted } from 'vue';
import axios from 'axios';

const hour = ref(new Date().getHours());
const temp = ref<number | null>(null);
const condition = ref<string>('cargando...');

const environment = computed(function() {
    if (hour.value >= 6 && hour.value < 18) {
        return {
            treeFilter: 'brightness-100',
            icon: '☀️'
        }
    } else if (hour.value >= 18 && hour.value < 20) {
        return {
            treeFilter: 'brightness-75 sepia-[.3]',
            icon: '🌇'
        }
    } else {
        return {
            treeFilter: 'brightness-50 contrast-125',
            icon: '🌙'
        }
    }
});

function toggleTime() {
    hour.value = (hour.value + 6) % 24;
}

onMounted(async () => {
    try {
        const response = await axios.get('/api/weather');
        temp.value = Math.round(response.data.main.temp);
        condition.value = response.data.weather[0].description;
    } catch (error) {
        console.error("No se pudo cargar el clima", error);
        temp.value = 35;
        condition.value = 'soleado';
    }
});
</script>

<template>
    <div 
        @click="toggleTime"
        class="relative w-[300px] h-[300px] rounded-2xl overflow-hidden border border-slate-800 shadow-xl cursor-pointer"
    >
        <img 
            src="/img/Background.png" 
            alt="Fondo"
            :class="['absolute inset-0 w-full h-full object-cover z-0 transition-all duration-1000', environment.treeFilter]"
        />

        <img 
            src="/img/Adenium.png" 
            alt="Oak"
            :class="['absolute inset-0 w-full h-full object-contain z-20 transition-all duration-1000', environment.treeFilter]"
        />
        <img 
            src="/img/Adenium2.png" 
            alt="Leaves"
            :class="['absolute inset-0 w-full h-full object-contain z-30 transition-all duration-1000', environment.treeFilter]"
        />
        <img 
            src="/img/Adenium3.png" 
            alt="Flowers"
            :class="['absolute inset-0 w-full h-full object-contain z-40 transition-all duration-1000', environment.treeFilter]"
        />
        
        <div class="absolute bottom-0 w-full h-1/5 bg-gradient-to-t from-black/60 to-transparent z-50 pointer-events-none"></div>

        <div class="absolute bottom-2 right-2 z-50 bg-black/50 backdrop-blur-md px-3 py-1 rounded-full border border-white/10 text-xs text-white flex items-center gap-2 capitalize">
            <span>{{ environment.icon }} {{ temp !== null ? temp + '°C' : '--°C' }}</span>
            <span class="opacity-70 border-l border-white/20 pl-2">Obregón, {{ condition }}</span>
        </div>
    </div>
</template>

<style scoped>
img {
    image-rendering: pixelated;
}
</style>