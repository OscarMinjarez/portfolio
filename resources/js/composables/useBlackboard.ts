import { ref, reactive, watch } from 'vue';
import { useStorage } from '@vueuse/core';

const currentFocus = ref<string | null>(null);
const logs = ref<{ time: string; msg: string; source: string }[]>([]);
const techInterests = reactive<Record<string, number>>({});
const isMinimized = useStorage('blackboard-minimized', true);
const unreadCount = ref<number>(0);

watch(isMinimized, (val) => {
    if (!val) {
        unreadCount.value = 0;
    }
});

export function useBlackboard() {

    function setFocus(focus: string) {
        if (currentFocus.value !== focus) {
            currentFocus.value = focus;
        }
    }

    function addLog(source: string, msg: string) {
        if (isMinimized.value) {
            unreadCount.value++;
        }
        const time = new Date().toLocaleTimeString('es-MX', { hour12: false });
        logs.value.push({ time, msg, source });
        if (logs.value.length > 15) {
            logs.value.shift();
        }
    }

    function addInterest(tech: string, points: number = 1) {
        techInterests[tech] = (techInterests[tech] || 0) + points;
    }

    return {
        currentFocus,
        logs,
        techInterests,
        isMinimized,
        unreadCount,
        setFocus,
        addLog,
        addInterest,
    };
}
