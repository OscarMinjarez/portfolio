<script setup lang="ts">
import { ref, computed, onMounted } from 'vue';
import axios from 'axios';

const hour = ref(new Date().getHours());
const temp = ref<number | null>(null);
const condition = ref<string>('cargando...');

const environment = computed(function () {
    if (hour.value >= 6 && hour.value < 18) {
        return {
            treeFilter: 'brightness-100',
            icon: '☀️',
        };
    } else if (hour.value >= 18 && hour.value < 20) {
        return {
            treeFilter: 'brightness-75 sepia-[.3]',
            icon: '🌇',
        };
    } else {
        return {
            treeFilter: 'brightness-50 contrast-125',
            icon: '🌙',
        };
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
        console.error('No se pudo cargar el clima', error);
        temp.value = 35;
        condition.value = 'soleado';
    }
});
</script>

<template>
    <div
        @click="toggleTime"
        class="relative w-[200px] h-[200px] sm:w-[240px] sm:h-[240px] lg:w-[300px] lg:h-[300px] cursor-pointer overflow-hidden rounded-sm border border-border shadow-2xl"
    >
        <img
            src="/img/Background.png"
            alt="Fondo"
            :class="[
                'absolute inset-0 z-0 h-full w-full object-cover transition-all duration-1000',
                environment.treeFilter,
            ]"
        />

        <img
            src="/img/Adenium.png"
            alt="Oak"
            :class="[
                'absolute inset-0 z-20 h-full w-full object-contain transition-all duration-1000',
                environment.treeFilter,
            ]"
        />
        <img
            src="/img/Adenium2.png"
            alt="Leaves"
            :class="[
                'absolute inset-0 z-30 h-full w-full object-contain transition-all duration-1000',
                environment.treeFilter,
            ]"
        />
        <img
            src="/img/Adenium3.png"
            alt="Flowers"
            :class="[
                'absolute inset-0 z-40 h-full w-full object-contain transition-all duration-1000',
                environment.treeFilter,
            ]"
        />

        <div
            class="pointer-events-none absolute bottom-0 z-50 h-1/5 w-full bg-gradient-to-t from-black/60 to-transparent"
        ></div>

        <div
            class="absolute right-1 bottom-1 z-50 flex items-center gap-1 rounded-full border border-white/5 bg-black/40 px-1.5 py-0.25 text-[8px] text-white/90 capitalize backdrop-blur-sm"
        >
            <span>{{ environment.icon }} {{ temp !== null ? temp + '°' : '--°' }}</span>
            <span class="border-l border-white/10 pl-1 opacity-60">{{ condition }}</span>
        </div>
    </div>
</template>

<style scoped>
img {
    image-rendering: pixelated;
}
</style>
