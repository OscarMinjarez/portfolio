import { ref, reactive } from 'vue';

const currentFocus = ref<HTMLElement | null>(null);
const logs = ref<{ time: string; msg: string; source: string }[]>([]);
const techInterests = reactive<Record<string, number>>({});

export function useBlackboard() {

    function setFocus(focus: string) {
        if (currentFocus.value !== focus) {
            currentFocus.value = focus;
            addLog('OBSERVER', `Foco actualizado a: [${focus}]`);
        }
    }

    function addLog(source: string, msg: string) {
        const time = new Date().toLocaleTimeString('es-MX', { hour12: false });
        logs.value.push({ time, msg, source });
        if (logs.value.length > 15) {
            logs.value.shift();
        }
    }

    function addTechInterest(tech: string, points: number = 1) {
        techInterests[tech] = (techInterests[tech] || 0) + points;
    }

    return {
        currentFocus,
        logs,
        techInterests,
        setFocus,
        addLog,
        addTechInterest,
    };
}
