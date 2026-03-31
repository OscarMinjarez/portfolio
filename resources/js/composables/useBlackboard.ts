import { ref, reactive } from 'vue';
import { useStorage } from '@vueuse/core';

const currentFocus = ref<string | null>(null);
const logs = ref<{ time: string; msg: string; source: string }[]>([]);
const techInterests = reactive<Record<string, number>>({});
const isMinimized = useStorage('blackboard-minimized', false);

export function useBlackboard() {

    function setFocus(focus: string) {
        if (currentFocus.value !== focus) {
            currentFocus.value = focus;
        }
    }

    function addLog(source: string, msg: string) {
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
        setFocus,
        addLog,
        addInterest,
    };
}
