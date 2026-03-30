import { useBlackboard } from "@/composables/useBlackboard";

export const trackDwell = {
    mounted(el: HTMLElement, binding: any) {
        const { addLog, addInterest } = useBlackboard();
        let timeout: ReturnType<typeof setTimeout>;
        const dwellTime = binding.value?.time || 2000;
        const targetName = binding.value?.name || 'Desconocido';
        const techCategory = binding.value?.tech;
        function handleMouseEnter() {
            console.log(`[Dwell] Iniciando rastreo en: ${targetName} (${dwellTime}ms)`);
            timeout = setTimeout(() => {
                console.log(`[Dwell] Umbral filtrado para: ${targetName}`);
                addLog('INTENT_RADAR', `Dwell detectado: Analizando [${targetName}]`);
                if (techCategory) addInterest(techCategory, 5);
            }, dwellTime);
        };
        function handleMouseLeave() {
            console.log(`[Dwell] Abortado: ${targetName}`);
            clearTimeout(timeout);
        };
        el.addEventListener('mouseenter', handleMouseEnter);
        el.addEventListener('mouseleave', handleMouseLeave);
        (el as any)._dwellHandlers = { handleMouseEnter, handleMouseLeave };
    },
    unmounted(el: HTMLElement) {
        const handlers = (el as any)._dwellHandlers;
        if (handlers) {
            el.removeEventListener('mouseenter', handlers.handleMouseEnter);
            el.removeEventListener('mouseleave', handlers.handleMouseLeave);
        }
    }
};