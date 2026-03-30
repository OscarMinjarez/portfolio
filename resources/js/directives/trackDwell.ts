import useAgent from "@/composables/useAgent";
import { useBlackboard } from "@/composables/useBlackboard";

export const trackDwell = {
    mounted(el: HTMLElement, binding: any) {
        const { addLog, addInterest } = useBlackboard();
        const { askAri } = useAgent();
        
        let timeout: ReturnType<typeof setTimeout>;
        const dwellTime = binding.value?.time || 500;
        const targetName = binding.value?.name || 'Desconocido';
        const techCategory = binding.value?.tech;

        function handleMouseEnter() {
            timeout = setTimeout(async () => {
                addLog('INTENT_RADAR', `Dwell confirmado en [${targetName}]. Procesando contexto...`);
                if (techCategory) addInterest(techCategory, 5);
                await askAri(targetName, techCategory);
            }, dwellTime);
        };

        function handleMouseLeave() {
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